<?php

namespace App\Http\Controllers\Admin\ContentManagement;

use App\Http\Controllers\Controller;
use App\Models\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GettingStartedController extends Controller
{
    public function index()
    {
        return view('admin.content-management.getting-started');
    }

    public function update(Request $request)
    {
        $request->validate([
            'videoUrl' => 'nullable|string',
            'video' => 'required_if:videoUrl,null|mimes:mp4,mov,avi,wmv,mpeg,flv',
            'description' => 'required|string|min:50',
            'title' => 'required|string|max:50',
            'completeTitle' => 'required|string',
            'completeContent' =>  'required|string'
        ]);

        $videoUrl = $request->videoUrl;

        if (($request->videoUrl == null) && $request->file('video')) {
            $file = $request->file('video');
            $name = (guid() . '.' . $file->getClientOriginalExtension());

            $diskName = 's3-pub-bizinabox';
            Storage::disk($diskName)->putFileAs("", $file, $name);
            $videoUrl = Storage::disk($diskName)->url($name);

            if (option('getting-started.video.url')) {
                $oldName = explode('/', option('getting-started.video.url'));
                if (Storage::disk($diskName)->exists(end($oldName))) {
                    Storage::disk($diskName)->delete($oldName);
                }
            }
        }

        $isYouTube = Str::contains($videoUrl, ['youtube.com', 'youtu.be']);
        if($isYouTube){
            $arr = explode('v=', $videoUrl);
            $videoUrl = end($arr);
        }

        option([
            'getting-started.video.description' => $request->description,
            'getting-started.video.title' => $request->title,
            'getting-started.video.url' => $videoUrl,
            'getting-started.video.isYouTube' => $isYouTube,
            'getting-started.complete.title' => $request->completeTitle,
            'getting-started.complete.content' => $request->completeContent,
        ]);

        return back()->with(['success' => 'Successfully Saved!']);
    }
}
