<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogAdsPosition extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = 'blog_ads_positions';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function getTypeNameAttribute()
    {
        if ($this->type == 'home') {
            return "Blog Home Page";
        } elseif ($this->type == 'category') {
            return "Category & Tag Home Page";
        } elseif ($this->type == 'detail') {
            return "Blog Detail Page";
        }
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function storeRule()
    {
        $rule['name'] = 'required|max:45';
        $rule['position_id'] = 'required';

        return $rule;
    }
    public function storeItem($request)
    {
        $position = $this;
        $position->name = $request->name;
        $position->status = $request->status? 1:0;
        $position->save();

        return $position;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(40)
            ->height(40)
            ->sharpen(10)
            ->nonQueued();
    }
}
