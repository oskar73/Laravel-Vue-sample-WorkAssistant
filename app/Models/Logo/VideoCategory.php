<?php

namespace App\Models\Logo;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoCategory extends BaseModel
{
    use HasFactory;
    use Sluggable;

    protected $table = 'video_categories';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [
        'parent_id.integer' => 'Choose valid parent category.',
    ];

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:1200';
        if ($request->category_id) {
            $rule['category_id'] = 'integer';
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
        return $this->hasMany(VideoCategory::class, 'parent_id');
    }
    public function approvedSubCategories()
    {
        return $this->hasMany(VideoCategory::class, 'parent_id')
//            ->withCount('approvedItems')
            ->where('status', 1)
            ->orderBy('order');
    }
    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'parent_id')->withDefault();
    }
    public function items()
    {
        return $this->hasMany(Video::class, 'category_id');
    }
    public function approvedItems()
    {
        return $this->hasMany(Video::class, 'category_id')->status(1);
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
}
