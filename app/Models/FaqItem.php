<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FaqItem extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = 'faq_items';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];


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
        $rule['category'] = 'required|integer';
        $rule['question'] = 'required|string|max:255';
        $rule['description'] = 'required';
        $rule['order'] = 'required|in:1,0';

        return $rule;
    }
    public function saveItem($request)
    {
        $item = $this;
        $item->title = $request->question;
        $item->category_id = $request->category;
        $item->description = $request->description;
        if ($request->links !== null) {
            $item->links = json_encode($request->links);
        } else {
            $item->links = null;
        }
        $item->status = $request->status? 1:0;
        $item->order = $request->order;
        $item->save();

        return $item;
    }
    public function saveMedia($request)
    {
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
        $collections = ['image', 'video'];
        $item->media()
            ->whereNotIn("id", $ids)
            ->whereIn("collection_name", $collections)
            ->get()
            ->each
            ->delete();

        $item->saveMedia($request);

        return $item;
    }

    public function filterItem($request)
    {
        try {
            $title = '<li class="breadcrumb-item clickable-item"><a href="#/all">FAQs</a></li>';

            $category = null;

            if ($request->keyword == '') {
                if ($request->category === 'all' || $request->item == null) {
                    if ($request->category === 'all') {
                        $filter_ids = FaqCategory::where("status", 1)
                            ->pluck("id")
                            ->toArray();
                    } else {
                        $filter_ids = FaqCategory::where('slug', $request->category)
                            ->where("status", 1)
                            ->pluck("id")
                            ->toArray();
                        $category = FaqCategory::where("slug", $request->category)
                            ->where("status", 1)
                            ->first();

                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->slug}'>" . $category->name . "</a></li>";
                    }

                    $items = FaqItem::whereIn("category_id", $filter_ids)
                        ->where('faq_items.status', 1)
                        ->select('faq_items.id', 'faq_items.title', 'faq_items.slug', 'faq_items.description', 'faq_items.category_id')
                        ->with('media', 'category')
                        ->orderBy('faq_items.created_at', 'DESC')
                        ->paginate(30);

                    $view = view('components.front.faqItem', compact('items', 'title', 'category'))->render();
                } else {
                    $item = FaqItem::where("slug", $request->item)
                        ->where("status", 1)
                        ->with("media", "category")
                        ->firstorfail();

                    $title .= "<li class='breadcrumb-item clickable-item'><a href='#/{$item->category->slug}'>" . $item->category->name . "</a></li>";
                    $view = view('components.front.faqItemDetail', compact('item', 'title'))->render();
                }
            } else {
                $keyword = $request->keyword;
                $items = FaqItem::where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%");
                    $query->orwhere('description', 'LIKE', "%$keyword%");
                })
                    ->where('faq_items.status', 1)
                    ->select('faq_items.id', 'faq_items.title', 'faq_items.slug', 'faq_items.description', 'faq_items.category_id')
                    ->with('media', 'category')
                    ->orderBy('faq_items.created_at', 'DESC')
                    ->paginate(30);

                $title = '<li class="breadcrumb-item"><a href="javascript:void(0);"> " '. $keyword. ' "  search result</a></li>';
                $view = view('components.front.faqItem', compact('items', 'title', 'category'))->render();
            }

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

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id')->withDefault();
    }
    public function registerMediaCollections(): void
    {
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
