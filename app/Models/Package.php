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

class Package extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    use Ratable;
    use RecurringPrice;
    use PurchaseFollowUp;
    use Meetable;
    use Formable;

    protected $table = 'packages';

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

    public function updateModuleRule($request)
    {
        $rule = [];
        if ($request->module_unlimit == null) {
            $rule['module_count'] = 'required|numeric|min:0';
        }
        if ($request->page_unlimit == null) {
            $rule['page_count'] = 'required|numeric|min:0';
        }
        if ($request->storage_unlimit == null) {
            $rule['storage'] = 'required';
        }
        if ($request->website_unlimit == null) {
            $rule['website_count'] = 'required|numeric|min:0';
        }
        $rule['free_domain_price'] = 'required|regex:/^\d+(\.\d{1,2})?$/';

        return $rule;
    }

    public function category()
    {
        return $this->belongsTo(PackageCategory::class, 'category_id')->withDefault();
    }

    public function services()
    {
        return $this->morphedByMany(Service::class, 'model', 'package_has_items', 'package_id', 'model_id')->withTimestamps();
    }

    public function lacartes()
    {
        return $this->morphedByMany(Lacarte::class, 'model', 'package_has_items', 'package_id', 'model_id')->withTimestamps();
    }

    public function plugins()
    {
        return $this->morphedByMany(Plugin::class, 'model', 'package_has_items', 'package_id', 'model_id')->withTimestamps();
    }

    public function modules()
    {
        return $this->morphedByMany(Module::class, 'model', 'package_has_items', 'package_id', 'model_id')->withTimestamps();
    }

    public function saveItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->description = $request->description;
        if ($request->links !== null) {
            $item->links = json_encode($request->links);
        } else {
            $item->links = null;
        }
        $item->featured = $request->featured ? 1 : 0;
        $item->status = $request->status ? 1 : 0;
        $item->new = $request->new ? 1 : 0;
        $item->order = $request->order;
        $item->save();

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
        if ($request->origin_image || $request->thumbnail !== null) {
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
        \Log::info($item);
        $item->saveMedia($request);
        \Log::info($item);

        return $item;
    }

    public function updateModule($request)
    {
        $item = $this;
        $item->module = $request->module_unlimit ? -1 : $request->module_count;
        $item->featured_module = -1;
        $item->page = $request->page_unlimit ? -1 : $request->page_count;
        $item->storage = $request->storage_unlimit ? -1 : $request->storage;
        $item->website = $request->website_unlimit ? -1 : $request->website_count;
        $item->domain = $request->free_domain_price;
        if ($item->step == 1) {
            $item->step = 2;
        }
        $item->save();

        $item->services()->sync($request->services);
        $item->plugins()->sync($request->plugins);
        $item->lacartes()->sync($request->lacartes);
        $item->modules()->sync($request->modules);

        return $item;
    }

    public function saveBizItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->category_id = $request->category;
        $item->package = 0;
        $item->description = $request->description;
        if ($request->links !== null) {
            $item->links = json_encode($request->links);
        } else {
            $item->links = null;
        }
        $item->featured = $request->featured ? 1 : 0;
        $item->status = $request->status ? 1 : 0;
        $item->new = $request->new ? 1 : 0;
        $item->order = $request->order;
        $item->save();

        return $item;
    }

    public function storeBizItem($request)
    {
        return $this->saveBizItem($request)
            ->saveMedia($request);
    }

    public function updateBizItem($request)
    {
        $item = $this->saveBizItem($request);
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

    public function filterItem($request)
    {
        try {
            $isDashboard = $request->has('dashboard');
            $selectedModulesNum = $request->get('selectedModulesNum', 0);
            $availableOnly = $request->get('availableOnly') === 'true';

            $items = Package::where('packages.status', 1)
                ->where('packages.package', 1)
                ->select('packages.id', 'packages.name', 'packages.slug', 'packages.featured', 'packages.new', 'packages.description', 'packages.module')
                ->with('media', 'standardPrice')
                ->orderBy('order')
                ->orderBy('featured', 'DESC')
                ->orderBy('new', 'DESC')
                ->orderBy('packages.created_at', 'DESC')
                ->paginate(9);

            $view = $isDashboard ? view('components.user.packageItem', compact('items', 'selectedModulesNum', 'availableOnly'))->render() : view('components.front.packageItem', compact('items'))->render();

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

    public function filterBizItem($request)
    {
        try {
            $title = '<li class="breadcrumb-item"><a href="#/all">Ready Made BIZ</a></li>';

            $category = null;

            if ($request->keyword == '') {
                if ($request->category === 'all') {
                    $filter_ids = PackageCategory::where(function ($query) {
                        $query->whereStatus(1)->whereParentId(0);
                    })
                        ->orWhere(function ($query) {
                            $query
                                ->where('parent_id', '!=', 0)
                                ->whereStatus(1)
                                ->whereHas('category', function ($query) {
                                    $query->whereStatus(1);
                                });
                        })
                        ->pluck("id")
                        ->toArray();

                    $query = Package::whereIn("category_id", $filter_ids);
                } else {
                    $category = PackageCategory::where('slug', $request->category)->first();

                    if ($category->parent_id === 0) {
                        $category_ids = $category->approvedSubCategories()->pluck("id")->toArray();
                        array_push($category_ids, $category->id);

                        $query = Package::whereIn("category_id", $category_ids);

                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->slug}'>" . $category->name . "</a></li>";
                    } else {
                        $query = Module::where("category_id", $category->id);

                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->category->slug}'>{$category->category->name}</a></li><li class='breadcrumb-item active' ><a href='#/{$category->slug}'>{$category->name}</a></li>";
                    }
                }
            } else {
                $keyword = $request->keyword;
                $query = Package::where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%");
                    $query->orwhere('description', 'LIKE', "%$keyword%");
                });

                $title = '<li class="breadcrumb-item"><a href="javascript:void(0);"> " ' . $keyword . ' "  search result</a></li>';
            }
            $query2 = $query->where('packages.status', 1)
                ->where('packages.package', 0)
                ->select('packages.id', 'packages.name', 'packages.slug', 'packages.featured', 'packages.new', 'packages.description')
                ->with('media', 'standardPrice');
            if ($request->orderBy === 'featured') {
                $items = $query2->orderBy('featured', 'DESC');
            } elseif ($request->orderBy === 'popular') {
                $items = $query2->orderBy('new', 'DESC');
            } else {
                $items = $query2->orderBy('new', 'DESC');
            }
            $items = $items->orderBy('packages.created_at', 'DESC')->paginate(9);
            $view = view('components.front.readymadeItem', compact('items', 'title', 'category'))->render();

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
