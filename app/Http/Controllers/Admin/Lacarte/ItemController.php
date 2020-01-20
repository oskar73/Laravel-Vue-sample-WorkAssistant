<?php

namespace App\Http\Controllers\Admin\Lacarte;

use App\Http\Controllers\Admin\AdminController;
use App\Models\AppointmentCategory;
use App\Models\AvailableWeekday;
use App\Models\Lacarte;
use App\Models\LacarteCategory;
use App\Models\PurchaseFollowupEmail;
use App\Models\PurchaseFollowupForm;
use Illuminate\Http\Request;
use Validator;

class ItemController extends AdminController
{
    public function __construct(Lacarte $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->select(['id', 'name', 'price', 'slashed_price', 'status', 'featured', 'new', 'created_at', 'category_id'])
                ->with(['media', 'category.category'])
                ->latest()
                ->get();
            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);

            $all = view('components.admin.lacarteItem', [
                'items' => $items,
                'selector' => "datatable-all",
            ])->render();

            $active = view('components.admin.lacarteItem', [
                'items' => $activeItems,
                'selector' => "datatable-active",
            ])->render();

            $inactive = view('components.admin.lacarteItem', [
                'items' => $inactiveItems,
                'selector' => "datatable-inactive",
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

        return view(self::$viewDir.'lacarte.item');
    }
    public function create()
    {
        $categories = LacarteCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir.'lacarte.itemCreate', compact('categories'));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->storeItem($request);
            $route = route('admin.lacarte.item.edit', $item->id) . "#/meeting";

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
        $item = $this->model->with('media', 'purchaseForm', 'purchaseEmail')
            ->where('id', $id)
            ->firstorfail();

        $categories = LacarteCategory::where('parent_id', '==', 0)
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

        return view(self::$viewDir . "lacarte.itemEdit", compact('item', 'categories', 'followEmails', 'followForms', 'appCats', 'data', 'weekArray', 'meetingSet'));
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
}
