<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LacarteCategory extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = 'lacarte_categories';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [
        'parent_id.integer' => 'Choose valid parent category.',
    ];

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:600';
        if ($request->category_id) {
            $rule['category_id'] = 'integer';
        } else {
            $rule['thumbnail'] = 'required';
        }
        $rule['parent_id'] = 'required|integer';

        return $rule;
    }
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
        return $this->hasMany(LacarteCategory::class, 'parent_id');
    }
    public function approvedSubCategories()
    {
        return $this->hasMany(LacarteCategory::class, 'parent_id')
            ->withCount('approvedItems')
            ->where('status', 1)
            ->orderBy('order');
    }
    public function category()
    {
        return $this->belongsTo(LacarteCategory::class, 'parent_id')->withDefault();
    }
    public function items()
    {
        return $this->hasMany(Lacarte::class, 'category_id');
    }
    public function approvedItems()
    {
        return $this->hasMany(Lacarte::class, 'category_id')->status(1);
    }
    public function scopeIsParent($query)
    {
        return $query->where('parent_id', 0);
    }
    public function isParent()
    {
        if ($this->parent_id === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(60)
            ->height(40)
            ->sharpen(10)
            ->nonQueued();
    }
}
