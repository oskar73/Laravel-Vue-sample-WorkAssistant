<?php

namespace App\Http\Controllers\Admin\Logo\Video;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Logo\Video;
use App\Models\Logo\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends AdminController
{
    public function __construct(Video $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->with(['category'])
                ->latest()
                ->get(['id', 'title', 'status', 'order', 'public', 'created_at', 'category_id']);

            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);

            $all = view('components.admin.graphic-designs.videoItem', [
                'items' => $items,
                'selector' => "datatable-all",
            ])->render();

            $active = view('components.admin.graphic-designs.videoItem', [
                'items' => $activeItems,
                'selector' => "datatable-active",
            ])->render();

            $inactive = view('components.admin.graphic-designs.videoItem', [
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

        return view(self::$viewDir.'logo.video.index');
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

        $all = view('components.admin.logo.videoItem', [
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
        $categories = VideoCategory::where('parent_id', '==', 0)
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir.'logo.video.create', compact('categories'));
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
        $item = $this->model->with(['media'])
            ->where('id', $id)
            ->firstorfail();

        $categories = VideoCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir . "logo.video.edit", compact('item', 'categories'));
    }
    public function detail($id)
    {
        $item = $this->model->with('media')
            ->where('id', $id)
            ->firstorfail();

        return view(self::$viewDir . "video.detail", compact('item'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));

            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $item = $this->model->findorfail($id)->updateItem($request);

            return $this->jsonSuccess($item);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
