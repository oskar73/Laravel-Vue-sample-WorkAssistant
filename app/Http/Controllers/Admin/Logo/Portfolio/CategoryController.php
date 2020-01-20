<?php

namespace App\Http\Controllers\Admin\Logo\Portfolio;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Logo\PaletteIdeaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends AdminController
{
    public function __construct(PaletteIdeaCategory $model)
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

            $all = view('components.admin.logo.portfolioCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.logo.portfolioCategory', [
                'categories' => $activeCats,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.logo.portfolioCategory', [
                'categories' => $inactiveCats,
                'selector' => "datatable-inactive",
            ])->render();
            $subcategory = view('components.admin.logo.portfolioCategory', [
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

        return view(self::$viewDir.'logo.portfolio.category');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $status = $request->status? 1:0;
            $request->merge(['status' => $status]);

            $data = $request->only('parent_id', 'name', 'description', 'status');
            if ($request->category_id == null) {
                $category = $this->model->create($data);

                // $category->addMedia($request->thumbnail)
                //     ->usingFileName(guid() . "." . $request->thumbnail->getClientOriginalExtension())
                //     ->toMediaCollection('image');

                $category->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                $category = $this->model->find($request->category_id);
                $category->update($data);

                if ($request->thumbnail) {
                    // $category->clearMediaCollection('image');
                    // $category->addMedia($request->thumbnail)
                    //     ->usingFileName(guid() . "." . $request->thumbnail->getClientOriginalExtension())
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

            return $this->jsonSuccess($category);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
