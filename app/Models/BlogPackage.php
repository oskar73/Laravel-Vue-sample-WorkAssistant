<?php

namespace App\Models;

use App\Traits\Formable;
use App\Traits\Meetable;
use App\Traits\PurchaseFollowUp;
use App\Traits\Ratable;
use App\Traits\RecurringPrice;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogPackage extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    use Ratable;
    use RecurringPrice;
    use PurchaseFollowUp;
    use Meetable;
    use Formable;

    protected $table = 'blog_packages';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function getPriceRedirectUrl()
    {
        if ($this->step !== 3) {
            $url = route('admin.blog.package.edit', $this->id) . "#/meeting";
        } else {
            $url = route('admin.blog.package.edit', $this->id) . "#/price";
        }

        return $url;
    }
    public function storeRule($request)
    {
        $rule['name'] = 'required|string|max:45';
        $rule['description'] = 'required|max:6000';
        $rule['order'] = 'required|in:1,0';
        if ($request->origin_image) {
            // $rule['origin_image'] = 'required|image|mimes:jpg,png,jpeg,gif|max:10240';
            $rule['origin_image'] = 'required';
        } else {
            $rule['thumbnail'] = 'nullable';
        }

        if ($request->images) {
            $rule['images.*'] = 'nullable|image|mimes:jpg,png,jpeg,gif|max:10240';
        }
        if ($request->videos) {
            $rule['videos.*'] = 'nullable|mimes:mp4,mov,ogg,qt,flv,3gp,avi,wmv,mpeg,mpg|max:102400';
        }
        if ($request->links) {
            $rule['links.*'] = 'required|max:191';
        }

        if ($request->unlimit == null) {
            $rule['post_number'] = 'required|integer|min:1';
        }

        return $rule;
    }
    public function saveItem($request, $save = 1)
    {
        $item = $this;
        $item->name = $request->name;
        $item->post_number = $request->unlimit? -1: $request->post_number;
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
        if ($save == 0) {
            $item->step = 2;
        }
        $item->save();

        return $item;
    }
    public function saveMedia($request)
    {
        if ($request->origin_image) {
            // $this->addMedia($request->origin_image)
            //     ->usingFileName(guid() . "." . $request->origin_image->getClientOriginalExtension())
            //     ->toMediaCollection('thumbnail');
            $this->addMediaFromBase64(json_decode($request->origin_image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('thumbnail');
        } elseif ($request->thumbnail) {
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
        return $this->saveItem($request, 1)
            ->saveMedia($request);
    }
    public function updateItem($request)
    {
        $item = $this->saveItem($request, 0);
        $ids = $request->oldItems ?? [];
        if ($request->thumbnail !== null || $request->origin_image != null) {
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
    //    public function updateMeeeting($request)
    //    {
    //        $item = $this;
    //        $item->meeting = $request->meeting?1:0;
    //        $item->form = $request->form?1:0;
    //        if($request->form)
    //        {
    //            $item->email_id = $request->followupEmail;
    //            $item->form_id = $request->followupForm;
    //        }else {
    //            $item->email_id = null;
    //            $item->form_id = null;
    //        }
    //        $item->step = 3;
    //        $item->save();
    //
    //        return $item;
    //    }
    public function filterItem($request)
    {
        try {
            $items = BlogPackage::where('blog_packages.status', 1)
                ->select(['blog_packages.id', 'blog_packages.name', 'blog_packages.slug','blog_packages.featured', 'blog_packages.new','blog_packages.description', 'blog_packages.post_number'])
                ->with('media', 'standardPrice')
                ->withCount("approvedPrices")
                ->orderBy('order')
                ->orderBy('featured', 'DESC')
                ->orderBy('new', 'DESC')
                ->orderBy('blog_packages.created_at', 'DESC')
                ->paginate(9);

            $view = view('components.front.blogPackageItem', compact('items'))->render();

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
