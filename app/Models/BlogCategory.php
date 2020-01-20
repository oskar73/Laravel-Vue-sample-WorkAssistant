<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Overtrue\LaravelSubscribe\Traits\Subscribable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogCategory extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    use Subscribable;

    protected $table = 'blog_categories';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    const CUSTOM_VALIDATION_MESSAGE = [
    ];

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:6000';
        $rule['tags.*'] = 'nullable|integer';
        if ($request->category_id) {
            $rule['category_id'] = 'integer';
        } else {
            $rule['thumbnail'] = 'required';
        }

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
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }
    public function approvedSubCategories()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')
            ->withCount('visiblePosts')
            ->where('status', 1)
            ->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id')->withDefault();
    }

    public function scopeIsParent($query)
    {
        return $query->where('parent_id', 0);
    }

    public function storeItem($request)
    {
        if ($request->category_id == null) {
            $category = $this;
        } else {
            $category = $this->findorfail($request->category_id);
        }
        $category->parent_id = $request->parent_id;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->status = $request->status?1:0;
        $category->save();

        if ($request->thumbnail) {
            $category->clearMediaCollection('image')
                ->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }
        if ($request->category_id != null && $category->parent_id != '0') {
            $category->subcategories()->update(['parent_id' => $request->parent_id]);
        }
        $category->tags()->sync($request->tags);

        return $category;
    }
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_category_tag', 'category_id', 'tag_id');
    }
    public function approvedTags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_category_tag', 'category_id', 'tag_id')->where('status', 1);
    }
    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
    public function visiblePosts()
    {
        return $this->hasMany(BlogPost::class, 'category_id')
            ->frontVisible();
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
