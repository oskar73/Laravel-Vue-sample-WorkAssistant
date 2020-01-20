<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Tutorial extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = "tutorials";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
    public function storeRule($request)
    {
        $rule['title'] = 'required|string|max:255';
        $rule['description'] = 'nullable';
        $rule['order'] = 'integer|min:1';
        $rule['gallery_order'] = 'required|in:1,0';
        $rule['category'] = 'required|integer';

        if ($request->images) {
            $rule['images.*'] = 'nullable|image|mimes:jpg,png,jpeg,gif|max:10240';
        }
        if ($request->videos) {
            $rule['videos.*'] = 'nullable|mimes:mp4,mov,ogg,qt,flv,3gp,avi,wmv,mpeg,mpg|max:102400';
        }
        if ($request->links) {
            $rule['links.*'] = 'required|max:191';
        }

        return $rule;
    }
    public function saveItem($request)
    {
        $item = $this;
        $item->title = $request->title;
        $item->category_id = $request->category;
        $item->gallery_order = $request->gallery_order;
        $item->description = $request->description;
        $item->order = $request->order;
        if ($request->links !== null) {
            $item->links = json_encode($request->links);
        } else {
            $item->links = null;
        }
        $item->status = $request->status? 1:0;
        $item->public = $request->public? 1:0;
        $item->save();

        if ($item->public == 1) {
            $item->modules()->sync([]);
        } else {
            $ids = [];

            if ($request->module) {
                foreach ($request->module as $key => $module_item) {
                    array_push($ids, $key);
                }
                $item->modules()->sync($ids);
            }
        }

        return $item;
    }
    public function saveMedia($request)
    {
        if ($request->origin_image) {
            $this->addMedia($request->origin_image)
                ->usingFileName(guid() . "." . $request->origin_image->getClientOriginalExtension())
                ->toMediaCollection('thumbnail');
        } elseif ($request->thumbnail) {
            // $this->addMediaFromBase64($request->thumbnail)
            //     ->usingFileName(guid() . ".jpg")
            //     ->toMediaCollection('thumbnail');

            $this->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('thumbnail');
        }
        foreach ($request->images ?? [] as $image) {
            $this->addMedia($image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }

        foreach ($request->videos ?? [] as $video) {
            $this->addMedia($video)
                ->toMediaCollection('video');
        }
    }
    public function storeItem($request)
    {
        return $this->saveItem($request)
            ->saveMedia($request);
    }

    public function updateItem($request)
    {
        $item = $this->saveItem($request);
        $ids = $request->oldItems ?? [];

        if ($request->origin_image || $request->thumbnail !== null) {
            $collections = ['video', 'thumbnail', 'image'];
        } else {
            $collections = ['video', 'image'];
        }
        $item->media()
            ->whereNotIn("id", $ids)
            ->whereIn("collection_name", $collections)
            ->get()
            ->each
            ->delete();

        $item->saveMedia($request);

        return $item;
    }
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'tutorial_modules', 'tutorial_id', 'module_id')->withTimestamps();
    }
    public function category()
    {
        return $this->belongsTo(TutorialCategory::class, 'category_id')->withDefault();
    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(80)
                    ->height(60)
                    ->sharpen(10)
                    ->nonQueued();
            });
        $this
            ->addMediaCollection('image')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(40)
                    ->height(40)
                    ->sharpen(10)
                    ->nonQueued();
            });
    }
}
