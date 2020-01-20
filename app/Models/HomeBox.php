<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomeBox extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name','description','status','link'];

    public function storeRule($request):array
    {
        $rule['name'] = 'required|string:max:191';
        $rule['description'] = 'required|string:max:191';
        $rule['link'] = 'required|url';

        if ($request->box_id) {
            $rule['box_id'] = 'integer';
        } else {
            $rule['image'] = 'required';
        }

        return $rule;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(20)
            ->height(20)
            ->sharpen(10)
            ->nonQueued();
    }
}
