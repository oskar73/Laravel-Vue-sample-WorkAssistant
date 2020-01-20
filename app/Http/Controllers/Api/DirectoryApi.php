<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;

class DirectoryApi
{

    public function getDirectoryCategories(Website $website)
    {
        return response()->json([
            'success' => true,
            'categories' => $website->getDirectoryCategories(),
        ]);
    }

    public function getDirectoryListings(Website $website)
    {
        return response()->json([
            'success' => true,
            'listings' => $website->getDirectoryListings(),
        ]);
    }
}
