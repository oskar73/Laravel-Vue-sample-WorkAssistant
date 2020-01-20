<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;
use Illuminate\Http\Request;
use Log;

class WebsiteApi
{
    public function saveWebsite(Website $website, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pages' => 'required',
        ]);

        $website->saveWebsite($request->all());

        return response()->json([
            'success' => true,
        ]);
    }

    public function createWebsite(Website $website, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'pages' => 'required',
        ]);

        $website->createWebsite($request->all());

        return response()->json([
            'success' => true,
        ]);
    }

    public function publishWebsite(Website $website)
    {
        Log::info($website);
        $website->publishWebsite();

        return response()->json([
            'success' => true,
        ]);
    }

    public function getWebsite(Website $website): \Illuminate\Http\JsonResponse
    {
        // dd('getWebsite');die();
        $website->load(['pages' => function ($query) {
            $query->where('status', 0);
        }]);

        return response()->json($website);
    }
}
