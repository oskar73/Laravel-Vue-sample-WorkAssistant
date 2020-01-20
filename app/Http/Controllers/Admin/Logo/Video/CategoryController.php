<?php

namespace App\Http\Controllers\Admin\Logo\Video;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Logo\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends AdminController
{
    public function __construct(VideoCategory $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model->where('parent_id', 0);
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $categories = $this->model->where('parent_id', 0)
                ->withCount('subcategories')
                ->get();

            $activeCats = $categories->where('status', 1);
            $inactiveCats = $categories->where('status', 0);

            $subcategories = $this->model->where('parent_id', '!=', 0)
                ->with('category')
                ->get();
            $all = view('components.admin.graphic-designs.videoCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.graphic-designs.videoCategory', [
                'categories' => $activeCats,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.graphic-designs.videoCategory', [
                'categories' => $inactiveCats,
                'selector' => "datatable-inactive",
            ])->render();
            $subcategory = view('components.admin.graphic-designs.videoCategory', [
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

        return view(self::$viewDir.'logo.video.category');
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

            $status = $request->status? 1:0;
            $request->merge(['status' => $status]);

            $data = $request->only('parent_id', 'name', 'description', 'status');
            if ($request->category_id == null) {
                $category = $this->model->create($data);
            } else {
                $category = $this->model->find($request->category_id);
                $category->update($data);

                if ($data['parent_id'] != 0) {
                    $category->subcategories()->update(['parent_id' => $data['parent_id']]);
                }
            }

            return $this->jsonSuccess($category);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
