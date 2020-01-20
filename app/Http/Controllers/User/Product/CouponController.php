<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\User\UserController;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCoupon;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Validator;

class CouponController extends UserController
{
    public function __construct(ProductCoupon $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir.'product.coupon.index');
    }
    public function product(Request $request)
    {
        try {
            $products = [];
            switch ($request->type) {
                case "category":
                    $products = ProductCategory::active()->my()->select('id', 'name')->get();

                    break;
                case "subCategory":
                    $products = ProductSubCategory::active()->my()->select('id', 'name')->get();

                    break;
                case "product":
                    $products = Product::active()->my()->select('id', 'name')->get();

                    break;
            }

            $result = '<option value="0">All Products</option>';
            foreach ($products as $product) {
                $result .= "<option value='{$product->id}'>{$product->name}</option>";
            }

            return response()->json([
                'status' => 1,
                'data' => $result,
                'type' => $products->getQueueableClass(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
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
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $coupon = $this->model->storeItem($request);

            return response()->json([
                'status' => 1,
                'data' => $coupon,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function edit(Request $request)
    {
        try {
            $coupon = $this->model->findorfail($request->id);

            return response()->json([
                'status' => 1,
                'data' => $coupon,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
