<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Admin\AdminController;
use App\Models\EmailPackage;
use App\Models\PurchaseFollowupEmail;
use App\Models\PurchaseFollowupForm;
use Illuminate\Http\Request;
use Validator;

class PackageController extends AdminController
{
    public function __construct(EmailPackage $model)
    {
        $this->model = $model;
        $this->sortModel = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->with(['media', 'approvedPrices'])
                ->latest()
                ->get();
            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);

            $all = view('components.admin.emailPackage', [
                'items' => $items,
                'selector' => 'datatable-all',
            ])->render();
            $active = view('components.admin.emailPackage', [
                'items' => $activeItems,
                'selector' => 'datatable-active',
            ])->render();
            $inactive = view('components.admin.emailPackage', [
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

        return view(self::$viewDir.'email.package');
    }
    public function create()
    {
        return view(self::$viewDir.'email.packageCreate');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->storeItem($request);
            $route = route('admin.email.package.edit', $item->id) . "#/price";

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

        return view(self::$viewDir . "email.packageEdit", compact('item', 'followEmails', 'followForms'));
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
                $url = route('admin.email.package.edit', $item->id) . "#/meeting";
            } else {
                $url = route('admin.email.package.edit', $item->id) . "#/price";
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
