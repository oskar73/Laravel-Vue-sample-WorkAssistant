<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Testimonial extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'testimonials';

    protected $guarded = ['created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function storeRule($request)
    {
        $rule['name'] = 'required|string|max:45';
        $rule['title'] = 'required|string|max:45';
        $rule['comment'] = 'required|max:600';
        if ($request->item_id == null) {
            // $rule['thumbnail'] = 'required';
            $rule['image'] = 'required';
        }

        return $rule;
    }
    public function storeItem($request)
    {
        if ($request->item_id == null) {
            $item = $this;
        } else {
            $item = $this->findorfail($request->item_id);
        }
        $item->name = $request->name;
        $item->title = $request->title;
        $item->comment = $request->comment;
        $item->status = $request->status? 1: 0;
        $item->save();

        if ($request->image) {
            // $item->clearMediaCollection('image');
            // $item->addMediaFromBase64($request->thumbnail)
            //     ->usingFileName(guid() . ".jpg")
            //     ->toMediaCollection('image');

            $item->clearMediaCollection("image")
                ->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }

        return $item;
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
