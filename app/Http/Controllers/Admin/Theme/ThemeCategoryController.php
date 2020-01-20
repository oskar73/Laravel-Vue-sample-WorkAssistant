<?php

namespace App\Http\Controllers\Admin\Theme;

use App\Http\Controllers\Admin\AdminController;
use App\Models\ThemeCategory;
use Illuminate\Http\Request;

class ThemeCategoryController extends AdminController
{
    public function __construct(ThemeCategory $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model->where('parent_id', 0);
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $categories = $this->model->where('parent_id', 0)
                ->whereNull('user_id')
                ->with('media')
                ->withCount('subcategories')
                ->get();

            $activeCats = $categories->where('status', 1);
            $inactiveCats = $categories->where('status', 0);

            $subcategories = $this->model->where('parent_id', '!=', 0)
                ->with('category', 'media')
                ->get();

            $all = view('components.admin.themeCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.themeCategory', [
                'categories' => $activeCats,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.themeCategory', [
                'categories' => $inactiveCats,
                'selector' => "datatable-inactive",
            ])->render();
            $subcategory = view('components.admin.themeCategory', [
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

        return view('admin.theme.category');
    }

    public function store(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'name' => 'required|string|max:45',
                    'description' => 'nullable|string|max:600',
                    'category_id' => 'nullable|integer',
                    'thumbnail' => 'required_without:category_id',
                    'parent_id' => 'required|integer',
                    'status' => 'nullable',
                ],
                [
                    'thumbnail.required_without' => 'Thumbnail is required.',
                ]
            );

            $data = $request->all();
            $data['status'] = isset($data['status']);
            if (empty($data['category_id'])) {
                $data['user_id'] = null; // for admin, user_id is null
                /** @var ThemeCategory $category */
                $category = $this->model->create($data);

                $category->addMediaFromBase64(json_decode($data['thumbnail'])->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                /** @var ThemeCategory $category */
                $category = $this->model->find($data['category_id']);
                $category->update($data);

                if ($data['thumbnail']) {
                    $category->clearMediaCollection("image")
                        ->addMediaFromBase64(json_decode($data['thumbnail'])->output->image)
                        ->usingFileName(guid() . ".jpg")
                        ->toMediaCollection('image');
                }
                if ($data['parent_id'] != '0') {
                    $category->subcategories()->update(['parent_id' => $data['parent_id']]);
                }
            }

            return response()->json(['status' => 1, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [$e->getMessage()],
            ]);
        }
    }
}
