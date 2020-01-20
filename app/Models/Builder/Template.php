<?php

namespace App\Models\Builder;

use App\Models\BaseModel;
use App\Traits\HasUniqueValue;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Blade;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

class Template extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    use HasUniqueValue;
    use SoftDeletes;

    protected $table = 'templates';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['image', 'favicon', 'logo'];

    protected $casts = [
        'data' => 'json',
    ];

    const CUSTOM_VALIDATION_MESSAGE = [
        'category_id.required' => 'Please choose category.',
        'header_id.integer' => 'Please choose valid header.',
        'footer_id.integer' => 'Please choose valid footer.',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function storeRule(): array
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:600';
        $rule['category_id'] = 'required|integer';
        $rule['header_id'] = 'integer|nullable';
        $rule['footer_id'] = 'integer|nullable';

        return $rule;
    }

    public function updateRule(): array
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'nullable|max:600';
        $rule['category_id'] = 'required|integer';
        $rule['header_id'] = 'nullable|integer|nullable';
        $rule['footer_id'] = 'nullable|integer|nullable';

        return $rule;
    }

    // attributes
    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl("preview");
    }

    public function getLogoAttribute(): string
    {
        return $this->getFirstMediaUrl("logo");
    }

    public function getFaviconAttribute(): string
    {
        return $this->getFirstMediaUrl("favicon");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'category_id')->withDefault();
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(TemplateHeader::class, 'header_id');
    }

    public function footer(): BelongsTo
    {
        return $this->belongsTo(TemplateFooter::class, 'footer_id');
    }

    public function pages()
    {
        return $this->hasMany(TemplatePage::class, 'template_id')->with(['sections' => function ($query) {
            $query->with(['category' => function ($query) {
                $query->with('sections');
            }]);
        }]);
    }

    public function approvedPages()
    {
        return $this->hasMany(TemplatePage::class, 'template_id')->where('status', 1);
    }

    public function approvedAllTemplatesQuery()
    {
        $category_ids = TemplateCategory::where("status", 0)
            ->pluck("id")
            ->toArray();
        $parent_ids = TemplateCategory::where("status", 0)
            ->where('parent_id', 0)
            ->pluck("id")
            ->toArray();
        $subcategory_ids = TemplateCategory::whereIn("parent_id", $parent_ids)
            ->pluck("id")
            ->toArray();
        $filter_ids = array_merge($category_ids, $subcategory_ids);
        $query = Template::whereNotIn("category_id", $filter_ids);

        return $query;
    }

    public function getTemplate($request, $pagination = 10, $selected_template = '')
    {
        if ($request->keyword == '') {
            if ($request->category === 'all') {
                $query = $this->approvedAllTemplatesQuery();
            } else {
                $category = TemplateCategory::where('slug', $request->category)->whereNull('user_id')->first();
                if ($category->parent_id === 0) {
                    $category_ids = $category->approvedSubCategories()->pluck("id")->toArray();
                    array_push($category_ids, $category->id);

                    $query = Template::whereIn("category_id", $category_ids);
                } else {
                    $query = Template::where("category_id", $category->id);
                }
            }
        } else {
            $keyword = $request->keyword;
            $query = Template::where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%");
                $query->orwhere('description', 'LIKE', "%$keyword%");
            });
        }
        if ($selected_template) {
            $query->where('templates.id', '!=', $selected_template);
        }

        $query2 = $query->where('templates.status', 1)
            ->select(['templates.id', 'templates.name', 'templates.header_id', 'templates.footer_id', 'templates.new', 'templates.featured', 'templates.slug'])
            ->with('media');
        if ($request->orderBy === 'featured') {
            $templates = $query2->orderBy('featured', 'DESC');
        } elseif ($request->orderBy === 'popular') {
            $templates = $query2->orderBy('used_count', 'DESC');
        } else {
            $templates = $query2->orderBy('new', 'DESC');
        }
        $templates = $templates->whereNull('user_id')->orderBy('templates.created_at', 'DESC')->paginate($pagination);

        return $templates;
    }

    public function filterTemplate($request, $type = 'front')
    {
        try {
            $title = '<li class="breadcrumb-item"><a href="#/all">Templates</a></li>';

            $category = null;

            if ($request->keyword == '') {
                if ($request->category !== 'all') {
                    $category = TemplateCategory::where('slug', $request->category)->first();
                    if ($category->parent_id === 0) {
                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->slug}'>" . $category->name . "</a></li>";
                    } else {
                        $title .= "<li class='breadcrumb-item'><a href='#/{$category->category->slug}'>{$category->category->name}</a></li><li class='breadcrumb-item active' ><a href='#/{$category->slug}'>{$category->name}</a></li>";
                    }
                }
            } else {
                $keyword = $request->keyword;

                $title = '<li class="breadcrumb-item"><a href="javascript:void(0);"> " ' . $keyword . ' "  search result</a></li>';
            }
            $templates = $this->getTemplate($request, 10, $request->template);
            $view = view('components.front.templateItem', compact('templates', 'title', 'category', 'type'))->render();

            $data['status'] = 1;
            $data['view'] = $view;
            $data['selected'] = null;
            if ($request->template) {
                $template = Template::findorfail($request->template);
                $view = view("components.user.selectedTemplate", compact("template", "type"))->render();
                $data['selected'] = $view;
            }

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
            ->addMediaCollection('preview')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(40)
                    ->height(60)
                    ->sharpen(10)
                    ->nonQueued();
            });
    }

    public function getHeader($preview)
    {
        $pages = $this->pages()
            ->with('template', 'activeSubPages')
            ->status(1)
            ->where('parent_id', 0)
            ->where('header', 1)
            ->orderBy('order')
            ->select('parent_id', 'name', 'url', 'template_id', 'id')
            ->get();
        $header = $this->header;
        $blade = Blade::compileString($header->content);
        $__data['pages'] = $pages;
        $__data['preview'] = $preview;
        $__data['__env'] = app(\Illuminate\View\Factory::class);
        $component = $this->bladeRender($blade, $__data);

        return $component;
    }

    public function getFooter($preview)
    {
        $pages = $this->pages()->with('template')->status(1)->where('footer', 1)->orderBy('footer_order')->select('parent_id', 'name', 'url', 'template_id', 'id')->get();
        $header = $this->footer;

        $blade = Blade::compileString($header->content ?? '');
        $__data['pages'] = $pages;
        $__data['preview'] = $preview;
        $__data['__env'] = app(\Illuminate\View\Factory::class);
        $component = $this->bladeRender($blade, $__data);

        return $component;
    }

    public function bladeRender($__php, $__data)
    {
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);

        try {
            eval('?' . '>' . $__php);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }

            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }

            throw new FatalThrowableError($e);
        }

        return ob_get_clean();
    }

    /**
     * @throws \Exception
     */
    public function savePreviewUrl($request): bool
    {
        // Get the uploaded file from the request
        $this->clearMediaCollection('preview')
            ->addMediaFromRequest('image')
            ->usingFileName(guid() . '.png')
            ->toMediaCollection('preview');

        return true;
    }

    public function getDesignUrlAttribute()
    {
        return route('admin.template.item.editPages', $this->id);
    }
}
