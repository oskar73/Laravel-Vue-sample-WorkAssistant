<?php

namespace App\Http\Controllers\Admin\ContentManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return view('admin.content-management.video');
    }

    public function update(Request $request)
    {
        $request->validate([
            'videoUrl' => 'required|string',
            'description' => 'required|string|min:50',
        ]);

        option(['home.video.description' => $request->description,'home.video.url' => $request->videoUrl]);

        return back()->with(['success' => 'Successfully Saved!']);
    }
}
