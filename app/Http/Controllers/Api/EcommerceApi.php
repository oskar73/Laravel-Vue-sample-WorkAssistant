<?php

namespace App\Http\Controllers\Api;

use App\Models\Module\EcommerceProduct;
use App\Models\Website;

class EcommerceApi
{

    public function getEcommerceCategories(Website $website)
    {
        return response()->json([
            'success' => true,
            'categories' => $website->getEcommerceCategories(),
        ]);
    }

    public function getEcommerceProducts(Website $website)
    {
        return response()->json([
            'success' => true,
            'products' => $website->getEcommerceProducts(),
        ]);
    }

    public function getProduct(Website $website, EcommerceProduct $product)
    {
        return response()->json([
           'product' => EcommerceProduct::with('standardPrice')
                ->with('sizes')
                ->with('variants')
                ->with('colors')
                ->with('prices')
                ->find($product->id),
        ]);
    }

    public function checkout(Request $request)
    {
        return response()->json([
            'data' => $request->all(),
        ]);
    }

    public function checkoutSuccess(Request $request)
    {
        return response()->json([
            'data' => $request->all(),
        ]);
    }
}
