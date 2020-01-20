<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Enums\StorageName;
use App\Http\Controllers\Admin\AdminController;
use App\Jobs\SiteMapGenerateJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class SeoController extends AdminController
{
    public function index()
    {
        $seo = option("seo", null);

        return view("admin.setting.seo", compact("seo"));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'status' => 0,
                'data' => $validation->errors(),
            ]);
        }

        $old_seo = option("seo", null);

        $seo['title'] = $request->title;
        $seo['keywords'] = $request->keywords;
        $seo['description'] = $request->description;
        $seo['head_code'] = $request->head_code;
        $seo['bottom_code'] = $request->bottom_code;

        if ($file = $request->image) {
            $name = "uploads/meta_img." . $file->getClientOriginalExtension();
            if (Storage::disk('s3-pub-bizinabox')->putFileAs('', $file, $name)) {
                $seo['image'] = Storage::disk('s3-pub-bizinabox')->url($name);
            }
        } else {
            $seo['image'] = optional($old_seo)['image'];
        }

        option(['seo' => $seo]);

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }

    public function generateSitemap()
    {
        $this->dispatch(new SiteMapGenerateJob);

        return back()->with('success', "It\'s generating in the background now. It won\'t take more than 5 minute. Please come back in a few and confirm updated time.");
    }

    public function downloadSitemap()
    {
        $path = 'static/sitemap.xml';

        if (Storage::disk(StorageName::BIZINABOX)->exists($path)) {
            $file = Storage::disk(StorageName::BIZINABOX)->get($path);

            $headers = [
                'Content-Type' => 'text/xml',
                'Content-Description' => 'Sitemap Download',
                'Content-Disposition' => 'attachment; filename=sitemap.xml',
                'filename' => 'sitemap.xml',
            ];

            return response($file, 200, $headers);
        } else {
            return back()->with('error', 'Sorry, sitemap file doesn\'t exist. Please generate it.');
        }
    }
}
