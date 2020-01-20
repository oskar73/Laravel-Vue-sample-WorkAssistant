<?php

namespace App\Models;

use App\Traits\Ratable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Yajra\DataTables\DataTables;

class Portfolio extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    use Ratable;

    protected $table = 'portfolios';

    protected $guarded = ['created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function links()
    {
        return json_decode($this->links);
    }

    public function images()
    {
        return $this->getMedia('image');
    }

    public function videos()
    {
        return $this->getMedia('video');
    }

    public function categories()
    {
        return $this->belongsTo(PortfolioCategory::class);
    }

    public function storeRule($request)
    {
        $rule['category'] = 'required|integer';
        $rule['title'] = 'required|string|max:255';
        $rule['description'] = 'required|max:6000';

        $rule['images.*'] = 'nullable|image|mimes:jpg,png,jpeg,gif|max:10240';
        $rule['videos.*'] = 'nullable|mimes:mp4,mov,ogg,qt,flv,3gp,avi,wmv,mpeg,mpg|max:102400';
        if ($request->links) {
            $rule['links.*'] = 'required';
        }
        $rule['order'] = 'required|in:1,0';

        return $rule;
    }

    public function saveItem($request)
    {
        $item = $this;
        $item->title = $request->title;
        $item->category_id = $request->category;
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
        if ($request->image) {
            $this->addMediaFromBase64(json_decode($request->image)->output->image)
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

        $collections = ['image', 'video', 'thumbnail'];

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
            $title = '<li class="breadcrumb-item"><a href="#/all">Portfolios</a></li>';

            $category = null;

            if ($request->keyword == '') {
                if ($request->category === 'all') {
                    $filter_ids = PortfolioCategory::where(function ($query) {
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

                    $query = Portfolio::whereIn("category_id", $filter_ids);
                } else {
                    $category = PortfolioCategory::where('slug', $request->category)->first();

                    if ($category->parent_id === 0) {
                        $category_ids = $category->approvedSubCategories()->pluck("id")->toArray();
                        array_push($category_ids, $category->id);

                        $query = Portfolio::whereIn("category_id", $category_ids);

                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->slug}'>" . $category->name . "</a></li>";
                    } else {
                        $query = Portfolio::where("category_id", $category->id);

                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->category->slug}'>{$category->category->name}</a></li><li class='breadcrumb-item active' ><a href='#/{$category->slug}'>{$category->name}</a></li>";
                    }
                }
            } else {
                $keyword = $request->keyword;
                $query = Portfolio::where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%");
                    $query->orwhere('description', 'LIKE', "%$keyword%");
                });

                $title = '<li class="breadcrumb-item"><a href="javascript:void(0);"> " '. $keyword. ' "  search result</a></li>';
            }
            $query2 = $query->where('portfolios.status', 1)
                ->select('portfolios.id', 'portfolios.title', 'portfolios.slug', 'portfolios.featured', 'portfolios.new', 'portfolios.description')
                ->with('media');
            if ($request->orderBy === 'featured') {
                $items = $query2->orderBy('featured', 'DESC');
            } elseif ($request->orderBy === 'popular') {
                $items = $query2->orderBy('new', 'DESC');
            } else {
                $items = $query2->orderBy('new', 'DESC');
            }
            $items = $items->orderBy('portfolios.created_at', 'DESC')->paginate(9);
            $view = view('components.front.portfolioItem', compact('items', 'title', 'category'))->render();

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
        return $this->belongsTo(\App\Models\PortfolioCategory::class, 'category_id')->withDefault();
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

    public function getUserDatatable()
    {
        $portfolio = user()->portfolio->all();

        return Datatables::of($portfolio)
        ->addColumn('category', function ($row) {
            return $row->category->name;
        })->editColumn('title', function ($row) {
            return $row->title;
        })->editColumn('description', function ($row) {
            return $row->description;
        })->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="c-badge c-badge-success">Active</span>';
            }
            return '';
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->editColumn('new', function ($row) {
            if ($row->new) {
                return '<span class="c-badge c-badge-success">New</span>';
            } else {
                return '';
            }
        })
        ->addColumn('action', function ($row) {
            return '
                    <a href="' . route('user.portfolio.edit', $row->slug) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Edit">
                       <i class="la la-edit"></i>
                    </a>';
        })->rawColumns(['status', 'new', 'action'])
            ->make(true);
    }
}
