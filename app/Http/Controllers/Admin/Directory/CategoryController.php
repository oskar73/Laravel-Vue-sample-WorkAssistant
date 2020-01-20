<?php

namespace App\Http\Controllers\Admin\Directory;

use App\Http\Controllers\Admin\AdminController;
use App\Models\DirectoryCategory;
use App\Models\DirectoryTag;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends AdminController
{
    public function __construct(DirectoryCategory $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model->where('parent_id', 0);
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $categories = $this->model->where('parent_id', 0)
                ->with('media', "tags:directory_tags.id,directory_tags.name")
                ->withCount('subcategories')
                ->get();

            $activeCats = $categories->where('status', 1);
            $inactiveCats = $categories->where('status', 0);

            $subcategories = $this->model->where('parent_id', '!=', 0)
                ->with('media')
                ->with('category')
                ->get();

            $all = view('components.admin.directoryCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.directoryCategory', [
                'categories' => $activeCats,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.directoryCategory', [
                'categories' => $inactiveCats,
                'selector' => "datatable-inactive",
            ])->render();
            $subcategory = view('components.admin.directoryCategory', [
                'categories' => $subcategories,
                'selector' => "datatable-subcategory",
            ])->render();

            $parents = '<option value="0">Set as Parent Category</option>';
            foreach ($categories as $category) {
                $parents .= "<option value='". $category->id ."'>". $category->name ."</option>";
            }
            $count['all'] = $categories->count();
            $count['active'] = $activeCats->count();
            $count['inactive'] = $inactiveCats->count();
            $count['subcategory'] = $subcategories->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'parents' => $parents,
                'subcategory' => $subcategory,
                'count' => $count,
            ]);
        }
        $tags = DirectoryTag::status(1)->get();

        return view(self::$viewDir.'directory.category', compact("tags"));
    }

    public function getSort()
    {
        try {
            $items = $this->sortModel->select('id', 'name')
                ->where('parent_id', 0)
                ->with("subcategories")
                ->orderBy('order')
                ->get();
            $view = '';
            foreach ($items as $item) {
                $view .= '<li data-id="'.$item->id.'">'.$item->name.'</li>';
                if ($item->parent_id == 0 && $item->subcategories->count()) {
                    foreach ($item->subcategories as $subcategory) {
                        $view .= '<li class="sub_item" data-id="'.$subcategory->id.'">'.$item->name." &#xbb; ".$subcategory->name.'</li>';
                    }
                }
            }

            return response()->json(compact('view'));
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $category = $this->model->storeItem($request);

            return $this->jsonSuccess($category);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
