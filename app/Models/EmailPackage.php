<?php

namespace App\Models;

use App\Traits\PurchaseFollowUp;
use App\Traits\RecurringPrice;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EmailPackage extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    use RecurringPrice;
    use PurchaseFollowUp;
    protected $table = 'email_packages';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function storeRule($request)
    {
        $rule['name'] = 'required|string|max:45';
        $rule['description'] = 'required|max:6000';
        $rule['order'] = 'required|in:1,0';
        if ($request->unlimit == null) {
            $rule['campaign_number'] = 'required|integer|min:1';
        }

        return $rule;
    }
    public function updateMeetingRule($request)
    {
        $rule = [];
        if ($request->form) {
            $rule['followupEmail'] = 'required|integer';
            $rule['followupForm'] = 'required|integer';
        }

        return $rule;
    }
    public function saveItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->campaign_number = $request->unlimit? -1: $request->campaign_number;
        $item->description = $request->description;

        if ($request->links !== null) {
            $item->links = json_encode($request->links);
        } else {
            $item->links = null;
        }

        $item->featured = $request->featured? 1:0;
        $item->status = $request->status? 1:0;
        $item->new = $request->new? 1:0;
        $item->order = $request->order;
        $item->save();

        return $item;
    }
    public function saveMedia($request)
    {
        if ($request->thumbnail !== null) {
            $this->addMediaFromBase64($request->thumbnail)
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

        return $this;
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
        if ($request->thumbnail !== null) {
            $collections = ['image', 'video', 'thumbnail'];
        } else {
            $collections = ['image', 'video'];
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
    public function updateMeeeting($request)
    {
        $item = $this;
        $item->meeting = $request->meeting?1:0;
        $item->form = $request->form?1:0;
        if ($request->form) {
            $item->email_id = $request->followupEmail;
            $item->form_id = $request->followupForm;
        } else {
            $item->email_id = null;
            $item->form_id = null;
        }
        $item->step = 3;
        $item->save();

        return $item;
    }
    public function filterItem($request)
    {
        try {
            $items = BlogPackage::where('email_packages.status', 1)
                ->select('email_packages.id', 'email_packages.name', 'email_packages.slug', 'email_packages.featured', 'email_packages.new', 'email_packages.description', 'email_packages.campaign_number')
                ->with('media', 'standardPrice')
                ->orderBy('order')
                ->orderBy('featured', 'DESC')
                ->orderBy('new', 'DESC')
                ->orderBy('email_packages.created_at', 'DESC')
                ->paginate(9);

            $view = view('components.front.emailPackageItem', compact('items'))->render();

            $data['status'] = 1;
            $data['view'] = $view;

            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(40)
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
