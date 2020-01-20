<?php

namespace App\Http\Controllers\Admin\ContentManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function howToBuild(): \Illuminate\Contracts\View\View
    {
        $data = option('home.how-to-build', []);

        return view('admin.content-management.how-to-build', compact('data'));
    }

    public function UpdateHowToBuild(Request $request): \Illuminate\Http\RedirectResponse
    {
        option([
            'home.how-to-build' => [
                'title' => $request->title ?? '',
                'description' => $request->description ?? '',
                'items' => $request->items ?? [],
            ],
        ]);

        return back()->with(['success' => 'Successfully updated!']);
    }
}
