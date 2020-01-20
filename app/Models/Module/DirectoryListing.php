<?php

namespace App\Models\Module;

use Illuminate\Support\Facades\Storage;

class DirectoryListing extends ModuleBaseModel
{
    protected $table = 'directory_listings';

    protected $appends = ["media"];

    public function getMediaAttribute()
    {
        $media = Media::where('model_type', 'App\Models\DirectoryListing')
                      ->where('model_id', $this->id)
                      ->first();

        if (!$media) return '';
        $filePath = "/media/" . $media->id . '/' . $media->file_name;
        if (Storage::disk($media->disk)->exists($filePath)) {
            return Storage::disk($media->disk)->url($filePath);
        } else {
            return '';
        }
    }
}
