<?php

namespace App\Http\Controllers\Admin\Tutorial;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Module;
use App\Models\Tutorial;
use App\Models\TutorialCategory;
use Illuminate\Http\Request;
use Validator;

class TutorialController extends AdminController
{
    public function __construct(Tutorial $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->with(['modules:modules.id,modules.name', 'category.category'])
                ->latest()
                ->get(['id', 'title', 'status', 'order', 'public', 'created_at', 'category_id']);

            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);

            $all = view('components.admin.tutorialItem', [
                'items' => $items,
                'selector' => "datatable-all",
            ])->render();

            $active = view('components.admin.tutorialItem', [
                'items' => $activeItems,
                'selector' => "datatable-active",
            ])->render();

            $inactive = view('components.admin.tutorialItem', [
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

        return view(self::$viewDir.'tutorial.index');
    }
    public function getCategory(Request $request)
    {
        if ($request->module == 'all') {
            $items = $this->model->select(['id', 'title', 'status', 'order', 'created_at', 'module_id'])
                ->with(['module'])
                ->latest()
                ->get();
        } else {
            $items = $this->model->select(['id', 'title', 'status', 'order', 'created_at', 'module_id'])
                ->where("module_id", $request->module)
                ->with(['module'])
                ->latest()
                ->get();
        }

        $all = view('components.admin.tutorialItem', [
            'items' => $items,
            'selector' => "datatable-all",
        ])->render();

        $count['all'] = $items->count();

        return response()->json([
            'status' => 1,
            'all' => $all,
            'count' => $count,
        ]);
    }

    public function create()
    {
        $modules = Module::select('id', 'name')
            ->status(1)
            ->get();

        $categories = TutorialCategory::where('parent_id', '==', 0)
            ->with('approvedSubCategories')
            ->status(1)
            ->get(['id', 'name']);

        return view(self::$viewDir.'tutorial.create', compact('modules', 'categories'));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));

            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $this->model->storeItem($request);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function edit($id)
    {
        $item = $this->model->with(['media', 'modules'])
            ->where('id', $id)
            ->firstorfail();

        $modules = Module::status(1)
            ->get(['id', 'name']);

        $categories = TutorialCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        $module_ids = $item->modules->pluck("id")->toArray();

        return view(self::$viewDir . "tutorial.edit", compact('item', 'modules', 'categories', 'module_ids'));
    }
    public function detail($id)
    {
        $item = $this->model->with('media')
            ->where('id', $id)
            ->firstorfail();

        return view(self::$viewDir . "tutorial.detail", compact('item'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));

            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $item = $this->model->findorfail($id)->updateItem($request);

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
