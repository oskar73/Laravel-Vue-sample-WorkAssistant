<?php

namespace App\Models\GraphicDesign;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Graphic extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use Sluggable;

    protected $table = 'graphics';

    protected $guarded = ['id'];

    protected $casts = [
        'front_settings' => 'json',
        'created_at' => 'date',
    ];

    protected $appends = ['image'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function designs()
    {
        return $this->hasMany(GraphicDesign::class, 'graphic_id');
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    public function storeItem($request)
    {
        if ($request->graphic_id == null) {
            $graphic = $this;
        } else {
            $graphic = $this->findorfail($request->graphic_id);
        }

        $graphic->title = $request->title;
        $graphic->height = $request->height;
        $graphic->width = $request->width;
        $graphic->status = $request->status ?? 1;
        $graphic->save();

        if ($request->thumbnail) {
            $thumbnail = json_decode($request->thumbnail)->output->image;
            $graphic->clearMediaCollection('thumbnail');
            $graphic->addMediaFromBase64($thumbnail)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('thumbnail');
        }

        return $graphic;
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
