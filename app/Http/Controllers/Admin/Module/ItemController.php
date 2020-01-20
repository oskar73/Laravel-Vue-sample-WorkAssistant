<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AppointmentCategory;
use App\Models\AvailableWeekday;
use App\Models\Module;
use App\Models\ModuleCategory;
use App\Models\PurchaseFollowupEmail;
use App\Models\PurchaseFollowupForm;
use Illuminate\Http\Request;
use Validator;

class ItemController extends AdminController
{
    public function __construct(Module $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->with(['media', 'category.category', 'approvedPrices'])
                ->latest()
                ->get();
            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);

            $all = view('components.admin.moduleItem', [
                'items' => $items,
                'selector' => 'datatable-all',
            ])->render();
            $active = view('components.admin.moduleItem', [
                'items' => $activeItems,
                'selector' => 'datatable-active',
            ])->render();
            $inactive = view('components.admin.moduleItem', [
                'items' => $inactiveItems,
                'selector' => 'datatable-inactive',
            ])->render();

            $count['all'] = $items->count();
            $count['active'] = $activeItems->count();
            $count['inactive'] = $inactiveItems->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'module.item');
    }
    public function create()
    {
        $categories = ModuleCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir.'module.itemCreate', compact('categories'));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->storeItem($request);
            $route = route('admin.module.item.edit', $item->id) . "#/price";

            return response()->json([
                'status' => 1,
                'data' => $route,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function edit($id)
    {
        if (request()->wantsJson()) {
            $prices = $this->model->find($id)->prices;
            $data = view('components.admin.modulePrice', compact('prices'))->render();

            return response()->json([
                'status' => 1,
                'data' => $data,
            ]);
        }
        $item = $this->model->with('media', 'purchaseForm', 'purchaseEmail', 'category')
            ->where('id', $id)
            ->firstorfail();

            $categories = ModuleCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        $followEmails = PurchaseFollowupEmail::whereStatus(1)
            ->select('id', 'title', 'content')
            ->get();

        $followForms = PurchaseFollowupForm::whereStatus(1)
            ->select('id', 'name', 'content')
            ->get();


        $appCats = AppointmentCategory::where("status", 1)
            ->select("id", "name")
            ->get();

        $meetingSet = $item->meetingSet;
        $availableWeek = new AvailableWeekday();
        $data = $availableWeek->getData(get_class($this->model), $item->id);
        $weekArray = $availableWeek::weekArray;

        return view(self::$viewDir . "module.itemEdit", compact('item', 'categories', 'followEmails', 'followForms', 'appCats', 'data', 'weekArray', 'meetingSet'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request, $id));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->find($id)->updateItem($request);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
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

            if ($item->step !== 3) {
                $url = route('admin.module.item.edit', $item->id) . "#/meeting";
            } else {
                $url = route('admin.module.item.edit', $item->id) . "#/price";
            }

            return response()->json([
                'status' => 1,
                'data' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
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
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
             ]);
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
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
