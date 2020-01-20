<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;
use App\Models\Builder\Template;
use App\Models\Website\Page;
use App\Models\Builder\TemplatePage;
use Illuminate\Http\Request;

class PageApi
{
    public function getPage(Website $website, Page $page)
    {
        $page->load('sections');

        return response()->json([
            'success' => true,
            'page' => $page,
        ]);
    }

    public function savePage(Website $website, Page $page, Request $request)
    {
        $request->validate([
            'sections' => 'required',
        ]);

        $page->savePage($request);

        return response()->json([
            'success' => true,
        ]);
    }

    public function getTemplate(Template $website, TemplatePage $page)
    {
        $page->load('sections');

        return response()->json([
            'success' => true,
            'page' => $page,
        ]);
    }

    public function saveTemplate(Template $website, TemplatePage $page, Request $request)
    {
        $request->validate([
            'sections' => 'required',
        ]);

        $page->savePage($request);

        return response()->json([
            'success' => true,
        ]);
    }
}
