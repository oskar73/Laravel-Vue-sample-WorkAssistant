<?php

namespace App\Http\Controllers\Admin\Portfolio;

use App\Http\Controllers\Admin\AdminController;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends AdminController
{
    public function __construct(PortfolioCategory $model)
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

            $all = view('components.admin.portfolioCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.portfolioCategory', [
                'categories' => $activeCats,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.portfolioCategory', [
                'categories' => $inactiveCats,
                'selector' => "datatable-inactive",
            ])->render();
            $subcategory = view('components.admin.portfolioCategory', [
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

        return view(self::$viewDir.'portfolio.category');
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

                $category->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                $category = $this->model->find($request->category_id);
                $category->update($data);

                if ($request->thumbnail) {
                    $category->clearMediaCollection('image')
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
