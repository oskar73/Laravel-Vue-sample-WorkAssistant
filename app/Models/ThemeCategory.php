<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ThemeCategory extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function subcategories()
    {
        return $this->hasMany(ThemeCategory::class, 'parent_id');
    }

    public function approvedSubCategories()
    {
        return $this->hasMany(ThemeCategory::class, 'parent_id')
            ->withCount('approvedTemplates')
            ->where('status', 1)
            ->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(ThemeCategory::class, 'parent_id')->withDefault();
    }
}
