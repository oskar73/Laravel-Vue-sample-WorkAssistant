<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;

class PortfolioApi
{

    public function getPortfolioCategories(Website $website)
    {
        return response()->json([
            'success' => true,
            'categories' => $website->getPortfolioCategories(),
        ]);
    }

    public function getPortfolioItems(Website $website)
    {
        return response()->json([
            'success' => true,
            'items' => $website->getPortfolioItems(),
        ]);
    }
}
