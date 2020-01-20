<?php

namespace App\Models\Module;

use Illuminate\Support\Facades\Storage;

class PortfolioItem extends ModuleBaseModel
{
    protected $table = 'portfolios';

    protected $appends = ["media"];

    public function getMediaAttribute()
    {
        $media = Media::where('model_type', 'App\Models\Portfolio')
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
