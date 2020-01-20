<?php

namespace App\Models\Builder;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TemplateCategory extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = 'template_categories';

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
        return $this->hasMany(TemplateCategory::class, 'parent_id');
    }
    public function approvedSubCategories()
    {
        return $this->hasMany(TemplateCategory::class, 'parent_id')
            ->withCount('approvedTemplates')
            ->where('status', 1)
            ->orderBy('order');
    }
    public function category()
    {
        return $this->belongsTo(TemplateCategory::class, 'parent_id')->withDefault();
    }
    public function templates()
    {
        return $this->hasMany(Template::class, 'category_id');
    }
    public function themes()
    {
        return $this->hasMany(Theme::class, 'category_id');
    }
    public function approvedTemplates()
    {
        return $this->hasMany(Template::class, 'category_id')->status(1);
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
