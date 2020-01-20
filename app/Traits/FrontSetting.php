<?php

namespace App\Traits;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FrontSetting
{
    use ImageUploadTrait;

    public function index(): View
    {
        $seo = option($this->moduleName . ".front.seo", []);

        return view("admin.{$this->moduleName}.front", compact("seo"));
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->headerImage) {
            $imagePaths = $this->uploadSlimImageFromRequest($request->headerImage, [
                'imageName' => "uploads/{$this->moduleName}-front-header-image.png",
                'thumbName' => "uploads/{$this->moduleName}-front-header-image-thumb.png",
            ]);

            option([
                "{$this->moduleName}.front.header.image" => $imagePaths['imagePath'],
                "{$this->moduleName}.front.header.image.thumb" => $imagePaths['thumbPath'],
            ]);
        }

        $seo = [
            "title" => $request->title ?? "",
            "keywords" => $request->keywords ?? "",
            "description" => $request->description ?? "",
            "image" => option("{$this->moduleName}.front.seo")['image'] ?? '',
        ];

        if ($request->boxImage) {

            $imagePaths = $this->uploadSlimImageFromRequest($request->boxImage, [
                'imageName' => "uploads/{$this->moduleName}-front-box-image.png",
                'thumbName' => "uploads/{$this->moduleName}-front-box-image-thumb.png",
            ]);

            option([
                "{$this->moduleName}.front.box.image" => $imagePaths['imagePath'],
                "{$this->moduleName}.front.box.image.thumb" => $imagePaths['thumbPath'],
            ]);
        }

        if ($request->image) {
            $seoImageName = "uploads/{$this->moduleName}-front-seo";

            $file = $request->file("image");
            $seoImageName = $seoImageName . "." . $file->getClientOriginalExtension();
            Storage::disk("s3-pub-bizinabox")->put($seoImageName, $file);

            $seo = array_merge($seo, ["image" => Storage::disk("s3-pub-bizinabox")->url($seoImageName)]);
        }

        option(["{$this->moduleName}.front.seo" => $seo]);

        return $this->jsonSuccess();
    }
}
