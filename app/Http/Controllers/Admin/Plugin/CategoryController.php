<?php

namespace App\Http\Controllers\Admin\Plugin;

use App\Http\Controllers\Admin\AdminController;
use App\Models\PluginCategory;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends AdminController
{
    public function __construct(PluginCategory $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model->where('parent_id', 0);
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $categories = $this->model->where('parent_id', 0)
                ->with('media')
                ->withCount('subcategories')
                ->get();

            $activeCats = $categories->where('status', 1);
            $inactiveCats = $categories->where('status', 0);

            $subcategories = $this->model->where('parent_id', '!=', 0)
                ->with('media')
                ->with('category')
                ->get();

            $all = view('components.admin.pluginCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.pluginCategory', [
                'categories' => $activeCats,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.pluginCategory', [
                'categories' => $inactiveCats,
                'selector' => "datatable-inactive",
            ])->render();
            $subcategory = view('components.admin.pluginCategory', [
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

        return view(self::$viewDir . 'plugin.category');
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
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $status = $request->status ? 1 : 0;
            $request->merge(['status' => $status]);
            $data = $request->only('parent_id', 'name', 'description', 'status');
            if ($request->category_id == null) {
                $category = $this->model->create($data);
                // $category->addMediaFromBase64($request->thumbnail)
                //     ->usingFileName(guid() . ".jpg")
                //     ->toMediaCollection('image');

                $category->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                $category = $this->model->find($request->category_id);
                $category->update($data);

                if ($request->thumbnail) {
                    // $category->clearMediaCollection('image');
                    // $category->addMediaFromBase64($request->thumbnail)
                    //     ->usingFileName(guid() . ".jpg")
                    //     ->toMediaCollection('image');

                    $category->clearMediaCollection("image")
                        ->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                        ->usingFileName(guid() . ".jpg")
                        ->toMediaCollection('image');
                }
                if ($request->parent_id != '0') {
                    $category->subcategories()->update(['parent_id' => $request->parent_id]);
                }
            }

            return response()->json(['status' => 1, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
