<?php

namespace App\Http\Controllers;

use App\Models\GraphicDesign\GraphicMask;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    public function getMediaContent($id, Request $request)
    {
        $request->validate(['type' => 'required']);

        $media = GraphicMask::where('content_id', $id)->firstOrFail();
        $headers = [
            'Content-Type' => $request->get('type'),
        ];

        return response($media->content, 200, $headers);
    }

    public function uploadWebsiteFile($webId, Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);
    }

    public function getWebsiteLink($id, $slug)
    {
        $website = Website::findOrFail($id);

        if (!auth()->user()->hasRole('admin') && $website->user_id !== auth()->user()->id) {
            abort(404, 'Invalid link');
        }

        return redirect()->to($website->getTemporaryUrl($slug));
    }
}
