<?php

namespace App\Models\Logo;

use App\Models\Tutorial;
use App\Models\TutorialCategory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Video extends Model implements HasMedia
{
    use HasFactory;
    use Sluggable;
    use InteractsWithMedia;

    protected $table = "videos";

    protected $guarded = ["id", "created_at", "updated_at"];

    public static function boot()
    {
        parent::boot();
        static::retrieved(function ($item) {
            $item->thumbnail = $item->thumbnail();
            if (! $item->link) {
                $item->link = $item->videoLink();
            }
        });

        static::saving(function ($item) {
            unset($item->thumbnail);
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function thumbnail()
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    public function videoLink()
    {
        return $this->getFirstMediaUrl('video');
    }

    public function storeRule($request)
    {
        $rule['title'] = 'required|string|max:255';
        $rule['description'] = 'nullable';
        $rule['order'] = 'integer|min:1';
        $rule['category'] = 'required|integer';
        $rule['thumbnail'] = 'required';

        if ($request->video) {
            $rule['video'] = 'nullable|mimes:mp4,mov,ogg,qt,flv,3gp,avi,wmv,mpeg,mpg|max:102400';
        } else {
            $rule['link'] = 'required|max:191';
        }

        return $rule;
    }
    public function saveItem($request)
    {
        $item = $this;
        $item->title = $request->title;
        $item->category_id = $request->category;
        $item->description = $request->description;
        $item->order = $request->order;
        if ($request->video) {
            $item->link = null;
        } else {
            $item->link = $request->link;
        }
        $item->status = $request->status? 1:0;
        $item->save();

        return $item;
    }
    public function saveMedia($request)
    {
        if ($request->thumbnail) {
            $this->clearMediaCollection('thumbnail')
                ->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                ->usingFileName(guid() . ".png")
                ->toMediaCollection('thumbnail');
        }

        if ($request->video) {
            $this->clearMediaCollection('video')
                ->addMedia($request->video)
                ->toMediaCollection('video');
        }

        if ($this->link) {
            $this->clearMediaCollection('video');
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

        $item->saveMedia($request);

        return $item;
    }
    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'category_id')->withDefault();
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
    public function filterItem($request)
    {
        $title = '<li class="breadcrumb-item clickable-item"><a href="#/all">Tutorials</a></li>';
        $category = null;
        if ($request->keyword == '') {
            if ($request->category !== 'all') {
                $category = TutorialCategory::where('slug', $request->category)->first();
                if ($category->parent_id === 0) {
                    $title .= "<li class='breadcrumb-item clickable-item'><a href='#/{$category->slug}'>" . $category->name . "</a></li>";
                } else {
                    $title .= "<li class='breadcrumb-item clickable-item'><a href='#/{$category->category->slug}'>{$category->category->name}</a></li><li class='breadcrumb-item active clickable-item' ><a href='#/{$category->slug}'>{$category->name}</a></li>";
                }
            }
        } else {
            $keyword = $request->keyword;

            $title = '<li class="breadcrumb-item"><a href="javascript:void(0);"> " '. $keyword. ' "  search result</a></li>';
        }

        return $this->getView($request, $title);
    }
    public function getView($request, $title, $pagination = 9)
    {
        if ($request->item) {
            $item = self::with('media')
                ->where('slug', $request->item)
                ->where("status", 1)
                ->where("public", 1)
                ->firstorfail();

            $data = view("components.front.tutorialItemDetail", compact("item", 'title'))->render();
        } else {
            if ($request->keyword == '') {
                if ($request->category === 'all') {
                    $query = $this->approvedAllTutorialsQuery();
                } else {
                    $category = TutorialCategory::where('slug', $request->category)->first();

                    if ($category->parent_id === 0) {
                        $category_ids = $category->approvedSubCategories()->pluck("id")->toArray();
                        array_push($category_ids, $category->id);

                        $query = Tutorial::whereIn("category_id", $category_ids);
                    } else {
                        $query = Tutorial::where("category_id", $category->id);
                    }
                }
            } else {
                $keyword = $request->keyword;
                $query = Tutorial::where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%");
                    $query->orwhere('description', 'LIKE', "%$keyword%");
                });
            }

            $items = $query->where('tutorials.status', 1)
                ->where('tutorials.public', 1)
                ->with('media')
                ->paginate($pagination);

            $data = view("components.front.tutorialItem", compact("items", 'title'))->render();
        }

        return $data;
    }
    public function approvedAllTutorialsQuery()
    {
        $category_ids = TutorialCategory::where("status", 0)
            ->pluck("id")
            ->toArray();
        $parent_ids = TutorialCategory::where("status", 0)
            ->where('parent_id', 0)
            ->pluck("id")
            ->toArray();
        $subcategory_ids = TutorialCategory::whereIn("parent_id", $parent_ids)
            ->pluck("id")
            ->toArray();
        $filter_ids = array_merge($category_ids, $subcategory_ids);
        $query = Tutorial::whereNotIn("category_id", $filter_ids);

        return $query;
    }
}
