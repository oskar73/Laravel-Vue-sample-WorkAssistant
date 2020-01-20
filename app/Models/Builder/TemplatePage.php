<?php

namespace App\Models\Builder;

use \Illuminate\Database\Eloquent\Relations as Relations;
use App\Enums\TemplatePageTypeEnum;
use App\Models\BaseModel;
use App\Models\Extensions\Sortable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TemplatePage extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use Sortable;

    protected $table = 'template_pages';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'data' => 'json',
    ];

    const TEMPLATE_PAGE_TYPES = [

    ];

    const CUSTOM_VALIDATION_MESSAGE = [
        'url.unique:' => 'Url parameter should be unique within template.',
    ];

    public const STATUS = [
        "preview" => 0,
        "published" => 1,
    ];

    public function storeRule($request, $template_id): array
    {
        $rule['page_name'] = 'required|max:255';

        if ($request->url) {
            $url_rule = 'alpha_dash|';
        } else {
            $url_rule = '';
        }
        if ($request->page_id) {
            $rule['url'] = $url_rule . 'max:255|unique:template_pages,url,' . $request->page_id . ',id,template_id,' . $template_id;
        } else {
            $rule['url'] = $url_rule . 'max:255|unique:template_pages,url,null,null,template_id,' . $template_id;
        }

        return $rule;
    }

    public function createPage($request): self
    {
        if ($request->page_id) {
            $model = $this->where('id', $request->page_id)->first();
        } else {
            $model = $this;
        }

        $model->template_id = $request->web_id;
        $model->parent_id = $request->parent_id ?? 0;
        $model->name = $request->page_name;
        $model->url = '/' . ($request->url ?? Str::slug($request->page_name));
        $model->data = [
            'title' => $request->page_name,
            'header' => config('builder.default.header'),
            'footer' => config('builder.default.footer'),
        ];
        $model->css = $request->page_css;
        $model->script = $request->page_script;
        $model->status = $request->page_status ? 1 : 0;
        $model->save();

        $model->assignOrder();
        $model->save();

        $this->addSections();

        return $model;
    }

    public function template(): Relations\BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function sections(): Relations\HasMany
    {
        return $this->hasMany(TemplatePageSection::class, 'page_id');
    }

    public function parent()
    {
        return $this->belongsTo(TemplatePage::class, 'parent_id')->withDefault();
    }

    public function subPages()
    {
        return $this->hasMany(TemplatePage::class, 'parent_id');
    }

    public function activeSubPages()
    {
        return $this->hasMany(TemplatePage::class, 'parent_id')->with('activeSubPages')->status(1)->orderBy('order');
    }

    public function dropDown($preview)
    {
        $menu = '';
        if ($this->activeSubPages->count() !== 0) {
            $menu .= "<ul class='sub-drop-down'>";
            foreach ($this->activeSubPages as $active) {
                $menu .= '<li class="sub-menu-item"><a class="sub-menu-link" href="' . $active->getUrl($preview) . '">' . $active->name . '</a>' . $active->dropDown($preview) . '</li>';
            }
            $menu .= '</ul>';
        }

        return $menu;
    }

    public function getUrl($preview)
    {
        if ($preview === 1) {
            return route('admin.template.item.preview', ['slug' => $this->template->slug, 'url' => $this->url]);
        } else {
            return route('template.view', ['slug' => $this->template->slug, 'url' => $this->url]);
        }
    }

    public function savePage($data)
    {
        DB::transaction(function () use ($data) {
            $this->name = $data['name'];
            $this->url = $data['url'];
            $this->css = $data['css'] ?? '';
            $this->script = $data['script'] ?? '';
            $this->status = 0;

            if (!empty($data['data'])) {
                $seoImage = $data['data']['seo']['image'] ?? null;
                if ($seoImage && strpos($seoImage, 'base64') > 0) {
                    $this->clearMediaCollection('seo-image')
                        ->addMediaFromBase64($seoImage)
                        ->usingFileName(guid() . ".png")
                        ->toMediaCollection('seo-image');
                    $data['data']['seo']['image'] = $this->getFirstMediaUrl('seo-image');
                }
                $this->data = $data['data'];
            }
            $this->save();

            if (!empty($data['sections'])) {

                TemplatePageSection::where('page_id', $this->id)->delete();

                foreach ($data['sections'] as $section) {
                    if ($section) {
                        $newSection = new TemplatePageSection();
                        $newSection->page_id = $this->id;
                        $newSection->name = $section['name'];
                        $newSection->category_id = $section['category_id'];
                        $newSection->data = $section['data'];
                        $newSection->save();
                    }
                }
            }
        });
    }

    public function replicatePage(): self
    {
        $clone = $this->replicate();
        $clone->name = $clone->replicateName();
        $clone->url = '/' . Str::slug($clone->name);
        $clone->push();
        $clone->save();

        foreach ($this->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $clone->id;
            $newSection->push();
            $newSection->save();
        }

        $clone->load('sections');

        return $clone;
    }

    public function replicateName(): string
    {
        $name = preg_replace('/[\s$@_*]+/', ' ', $this->name);
        $name = preg_replace('/ \d$/', '', $name);
        $count = self::where('template_id', $this->template_id)->where('name', 'like', $name . '%')->count();

        return $name . ' ' . $count;
    }

    public function createTemplatePage($template_id, $parent_id, $title, $is_new_page = false)
    {
        $this->template_id = $template_id;
        $this->parent_id = $parent_id;
        $this->name = $title;
        $this->data = [
            'title' => $title,
            'header' => config('builder.default.header'),
            'footer' => config('builder.default.footer'),
            'theme' => config('builder.default.theme'),
            'setting' => config('builder.default.setting'),
        ];

        if ($is_new_page) {
            $this->type = TemplatePageTypeEnum::NEW_PAGE;
            $this->url = '/new-page';
        } else {
            $this->type = TemplatePageTypeEnum::HOME_PAGE;
            $this->url = '/';
        }

        $this->status = 0;
        $this->save();

        $this->addSections($is_new_page);
    }

    public function addSections($hide_sections = false)
    {
        // add sections to template
        $section_categories = SectionCategory::where('status', true)
            ->orderBy('order')
            ->whereNotIn('slug', ['header', 'footer'])
            ->get();
        foreach ($section_categories as $section_category) {
            $section = $section_category->sections()->where('status', true)->first();
            if ($section) {
                for ($i = 0; $i < $section_category->limit_per_page; $i++) {
                    $newSection = new TemplatePageSection();
                    $newSection->page_id = $this->id;
                    $newSection->name = $section->name;
                    $newSection->category_id = $section->category_id;
                    $data = $section->data;
                    $data->setting->visible = !$hide_sections;
                    $newSection->data = $data;
                    $newSection->save();
                }
            }
        }
    }
}
