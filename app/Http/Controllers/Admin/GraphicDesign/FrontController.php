<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use App\Traits\ImageUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{
    use ImageUploadTrait;
    public function index(): View
    {
        $slug = request()->get('slug');
        if($slug) {
            $graphic = Graphic::findBySlug($slug);
        } else {
            $graphic = Graphic::first();
        }
        $fontSetting = $graphic->front_settings;
        $graphics = Graphic::all();

        return view("admin.graphic-designs.front", [
            'graphic' => $graphic,
            'graphics' => $graphics,
            'frontSetting' => $fontSetting,
        ]);
    }

    public function store(Request $request, $slug): JsonResponse
    {
        $graphic = Graphic::findBySlug($slug);
        $fontSetting = [];

        if ($request->headerImage) {
            $imagePaths = $this->uploadSlimImageFromRequest($request->headerImage, [
                'imageName' => "uploads/{$graphic->slug}-front-header-image.png",
                'thumbName' => "uploads/{$graphic->slug}-front-header-image-thumb.png",
            ]);

            $fontSetting['header_image'] = $imagePaths['imagePath'];
            $fontSetting['header_image_thumbnail'] = $imagePaths['thumbPath'];
        }

        if ($request->boxImage) {
            $imagePaths = $this->uploadSlimImageFromRequest($request->boxImage, [
                'imageName' => "uploads/{$graphic->slug}-front-box-image.png",
                'thumbName' => "uploads/{$graphic->slug}-front-box-image-thumb.png",
            ]);

            $fontSetting['box_image'] = $imagePaths['imagePath'];
            $fontSetting['box_image_thumbnail'] = $imagePaths['thumbPath'];
        }

        $seo = [
            "title" => $request->title ?? "",
            "keywords" => $request->keywords ?? "",
            "description" => $request->description ?? "",
        ];
        if ($request->image) {
            $file = $request->file("image");
            $path = Storage::disk("s3-pub-bizinabox")->put("uploads", $file);
            $seo['image'] = Storage::disk("s3-pub-bizinabox")->url($path);
        }

        $fontSetting['seo'] = $seo;

        $graphic->front_settings = $fontSetting;
        $graphic->save();

        return $this->jsonSuccess();
    }
}
