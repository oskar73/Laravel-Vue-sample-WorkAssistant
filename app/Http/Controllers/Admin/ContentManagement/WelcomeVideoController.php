<?php

namespace App\Http\Controllers\Admin\ContentManagement;

use App\Http\Controllers\Controller;
use App\Models\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeVideoController extends Controller
{
    public function index()
    {
        return view('admin.content-management.welcome-video');
    }

    public function update(Request $request)
    {
        $request->validate([
            'videoUrl' => 'nullable|string',
            'video' => 'required_if:videoUrl,null|mimes:mp4,mov,avi,wmv,mpeg,flv',
            // 'description' => 'required|string|min:50',
        ]);

        $videoUrl = $request->videoUrl;

        if (($request->videoUrl == null) && $request->file('video')) {
            $file = $request->file('video');
            $name = (guid() . '.' . $file->getClientOriginalExtension());

            $diskName = 's3-pub-bizinabox';
            Storage::disk($diskName)->putFileAs("", $file, $name);
            $videoUrl = Storage::disk($diskName)->url($name);

            if (option('welcome.video.url')) {
                $oldName = explode('/', option('welcome.video.url'));
                if (Storage::disk($diskName)->exists(end($oldName))) {
                    Storage::disk($diskName)->delete($oldName);
                }
            }
        }

        option(['welcome.video.description' => $request->description, 'welcome.video.url' => $videoUrl]);

        return back()->with(['success' => 'Successfully Saved!']);
    }
}
