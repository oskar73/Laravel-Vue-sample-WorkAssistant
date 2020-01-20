<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class NewsletterAdsType extends BaseModel
{
    use Sluggable;

    protected $table = 'newsletter_ads_types';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function storeRule()
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:6000';
        $rule['width'] = 'required|integer|min:50';
        $rule['height'] = 'required|integer|min:50';

        return $rule;
    }

    public function storeItem($request)
    {
        $type = $this;
        $type->name = $request->name;
        $type->description = $request->description;
        $type->width = $request->width;
        $type->height = $request->height;
        $type->status = $request->status ? 1 : 0;
        $type->save();

        return $type;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function positions()
    {
        return $this->hasMany(NewsletterAdsPosition::class, 'type_id');
    }
}
