<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\User\UserController;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SubCategoryController extends UserController
{
    public function __construct(ProductSubCategory $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $subCategories = $this->model->my()->get();

            return Datatables::of($subCategories)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
                })
                ->addColumn('categoryName', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0);"
                            class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                            data-id="'.$row->id.'" data-product_category_id="'.$row->category->id.'" data-name="'.$row->name.'" data-status="'.$row->status.'"
                        >
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete">
                            <span>
                                <span>Delete</span>
                            </span>
                        </a>';
                })->rawColumns(['checkbox', 'action'])->make(true);
        }
        $categories = ProductCategory::with('subCategories')->select('id', 'name')->my()->get();


        $subCategories = $this->model::with('category')->my()->get();

        // dd($subCategories[0]->category->name);

        return view(self::$viewDir.'product.sub-category', compact('categories'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->model->storeRule($request));

        if ($validation->fails()) {
            return response()->json([
                'status' => 0,
                'data' => $validation->errors(),
            ]);
        }

        $subCategory = $this->model->storeUpdateItem($request);

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->updateRule($id));
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $item = $this->model->findorfail($id)->storeUpdateItem($request);

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
    public function switchListing(Request $request)
    {
        try {
            $action = $request->action;
            $subCategories = $this->model->whereIn('id', $request->ids)->get();

            if ($action === 'active') {
                $subCategories->each->update(['status' => true]);
            } elseif ($action === 'inactive') {
                $subCategories->each->update(['status' => false]);
            } elseif ($action === 'delete') {
                $subCategories->each->delete();
            }

            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
