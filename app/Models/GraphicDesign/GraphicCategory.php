<?php

namespace App\Models\GraphicDesign;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GraphicCategory extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use Sluggable;

    protected $table = 'graphic_categories';

    protected $guarded = ['id'];

    protected $appends = ['image'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    public function graphic(): BelongsTo
    {
        return $this->belongsTo(GraphicCategory::class, 'graphic_id', 'id');
    }

    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(GraphicDesign::class, 'graphic_design_categories', 'category_id', 'design_id')
            ->withPivot("order")
            ->where('status', 1);
    }

    public function storeItem($request)
    {
        if ($request->category_id == null) {
            $category = $this;
        } else {
            $category = $this->findorfail($request->category_id);
        }

        $category->graphic_id = $request->graphic_id;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        $thumbnail = json_decode($request->thumbnail)->output->image;
        $category->clearMediaCollection('thumbnail');
        $category->addMediaFromBase64($thumbnail)
            ->usingFileName(guid() . ".jpg")
            ->toMediaCollection('thumbnail');

        return $category;
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->registerMediaConversions(function () {
                $this
                    ->addMediaConversion('thumb')
                    ->width(40)
                    ->height(40)
                    ->sharpen(10)
                    ->nonQueued();
            });
    }
}
