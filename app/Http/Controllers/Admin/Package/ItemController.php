<?php

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AppointmentCategory;
use App\Models\AvailableWeekday;
use App\Models\Lacarte;
use App\Models\Module;
use App\Models\Package;
use App\Models\Plugin;
use App\Models\PurchaseFollowupEmail;
use App\Models\PurchaseFollowupForm;
use App\Models\Service;
use Illuminate\Http\Request;
use Validator;

class ItemController extends AdminController
{
    public function __construct(Package $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model->where('package', 1);
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->with(['media', 'approvedPrices', 'services', 'plugins', 'lacartes', 'modules'])
                ->wherePackage(1)
                ->latest()
                ->get();
            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);

            $count['all'] = $items->count();
            $count['active'] = $activeItems->count();
            $count['inactive'] = $inactiveItems->count();

            $all = view('components.admin.packageItem', [
                'items' => $items,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.packageItem', [
                'items' => $activeItems,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.packageItem', [
                'items' => $inactiveItems,
                'selector' => "datatable-inactive",
            ])->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'package.item');
    }
    public function create()
    {
        return view(self::$viewDir.'package.itemCreate');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->storeItem($request);
            $route = route('admin.package.item.edit', $item->id) . "#/item";

            return response()->json([
                'status' => 1,
                'data' => $route,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function edit($id)
    {
        if (request()->wantsJson()) {
            $prices = $this->model->find($id)->prices;
            $data = view('components.admin.packagePrice', compact('prices'))->render();

            return response()->json([
                'status' => 1,
                'data' => $data,
            ]);
        }
        $item = $this->model->with('media', 'purchaseForm', 'purchaseEmail')
            ->where('id', $id)
            ->firstorfail();

        $followEmails = PurchaseFollowupEmail::whereStatus(1)
            ->select('id', 'title', 'content')
            ->get();

        $followForms = PurchaseFollowupForm::whereStatus(1)
            ->select('id', 'name', 'content')
            ->get();

        $services = Service::status(1)
            ->get();
        $plugins = Plugin::status(1)
            ->get();
        $lacartes = Lacarte::status(1)
            ->get();
        $modules = Module::with('standardPrice')
            ->status(1)
            ->get();

        $appCats = AppointmentCategory::where("status", 1)
            ->select("id", "name")
            ->get();
        $meetingSet = $item->meetingSet;
        $availableWeek = new AvailableWeekday();
        $data = $availableWeek->getData(get_class($this->model), $item->id);
        $weekArray = $availableWeek::weekArray;

        return view(self::$viewDir . "package.itemEdit", compact('item', 'followEmails', 'followForms', 'services', 'plugins', 'lacartes', 'modules', 'appCats', 'data', 'weekArray', 'meetingSet'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->find($id)->updateItem($request);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function updateModule(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->updateModuleRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            $item = $this->model->find($id)->updateModule($request);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function createPrice(Request $request, $id)
    {
        try {
            if ($request->edit_price == null) {
                $validation = Validator::make($request->all(), $this->model->createPriceRule($request));
                if ($validation->fails()) {
                    return response()->json(['status' => 0, 'data' => $validation->errors()]);
                }

                $item = $this->model->find($id)->createPrice($request);
            } else {
                $validation = Validator::make($request->all(), $this->model->updatePriceRule($request));
                if ($validation->fails()) {
                    return response()->json(['status' => 0, 'data' => $validation->errors()]);
                }

                $item = $this->model->find($id)->updatePrice($request);
            }

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function deletePrice(Request $request, $id)
    {
        try {
            $price = $this->model->find($id)
                ->prices()
                ->where("id", $request->id)
                ->firstorfail();
            if ($price->recurrent == 1) {
                $this->model->deletePlan($price->plan_id);
            }
            $price->delete();

            return response()->json([
                'status' => 1,
                'data' => $id,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function updateMeeting(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->updateMeetingRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->find($id)->updateMeeeting($request);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
