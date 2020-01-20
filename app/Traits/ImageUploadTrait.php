<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait ImageUploadTrait
{
    public function uploadSlimImageFromRequest($image, array $options): array
    {

        $imagePaths = [];

        $imageName = $options['imageName'];
        $diskName = $options['disk'] ?? 's3-pub-bizinabox';

        $imageData = json_decode($image)->output->image;
        $imageData = preg_replace('/data:image\/((jpeg)|(png)|(jpg)|(gif));base64,/i', '', $imageData);
        $imageData = base64_decode($imageData);

        Storage::disk($diskName)->put($imageName, $imageData);
        $imagePaths['imagePath'] = Storage::disk($diskName)->url($imageName);

        if (! empty($thumbImageName = $options['thumbName'])) {
            $image = Image::make($imageData);
            $thumbnail = $image->fit($options['thumbWidth'] ?? 60, $options['thumbHeight'] ?? 60);
            $thumbnailData = $thumbnail->encode('data-url');
            Storage::disk($diskName)->put($thumbImageName, $thumbnailData);
            $imagePaths['thumbPath'] = Storage::disk($diskName)->url($thumbImageName);
        }

        return $imagePaths;
    }
}
