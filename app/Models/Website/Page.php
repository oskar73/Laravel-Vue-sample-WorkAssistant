<?php

namespace App\Models\Website;

use App\Models\Builder\Template;
use App\Models\Extensions\Sortable;
use App\Models\Website;
use Illuminate\Database\Eloquent\Relations as Relations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends WebsiteBaseModel implements HasMedia
{
    use Sortable;
    use InteractsWithMedia;

    protected $connection = 'mysql2';
    protected $table = 'pages';

    protected $casts = ['data' => 'json'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sections(): Relations\HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id');
    }

    public function savePage($data): void
    {
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
               $data['seo']['image'] = $this->getFirstMediaUrl('seo-image');
           }
           $this->data = $data['data'];
       }
        $this->save();

        if (!empty($data['sections'])) {
            PageSection::where('page_id', $this->id)->delete();

            foreach ($data['sections'] as $section) {
                $newSection = new PageSection();
                $newSection->page_id = $this->id;
                $newSection->name = $section['name'];
                $newSection->category_id = $section['category_id'];
                $newSection->data = $section['data'];
                $newSection->save();
            }
        }
    }

    public function addSections($sections, $visible = null)
    {
        foreach ($sections as $section) {
            $newSection = new PageSection();
            $newSection->page_id = $this->id;
            $newSection->name = $section['name'];
            $newSection->category_id = $section['category_id'];
            $data = $section['data'];
            if (!is_null($visible)) {
                $data['setting']['visible'] = $visible;
            }
            $newSection->data = $data;
            $newSection->save();
        }
    }

    public function replicatePage(): self
    {
        $clone = $this->replicate();
        $clone->name = $clone->replicateName();
        $clone->url = '/'.Str::slug($clone->name);
        $clone->push();
        $clone->save();

        foreach ($this->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $clone->id;
            $newSection->push();
            $newSection->save();
        }

        $clone->sections = $clone->sections;

        return $clone;
    }

    protected function replicateName(): string
    {
        $name = preg_replace('/[\s$@_*]+/', ' ', $this->name);
        $name = preg_replace('/ \d$/', '', $name);
        $pageCount = self::where('web_id', $this->web_id)->where('name', 'like', $name . '%')->count();

        return $name . ' ' . $pageCount;
    }

    public function web()
    {
        return $this->belongsTo(Website::class, 'web_id');
    }

    public function createPage($request)
    {
        $this->web_id = $request->web_id;
        $this->parent_id = $request->parent_id;
        $this->name = $request->page_name;
        $this->url = '/'.Str::slug($request->page_name, '-');
        $this->css = $request->page_css;
        $this->data = [
            'title' => $request->page_name,
        ];
        $this->script = $request->page_script;
        $this->status = 0;
        $this->save();

        $this->assignOrder();

        $this->save();

        $this->copyContentFromTemplate();

        return $this;
    }

    private function copyContentFromTemplate()
    {
        $template = Template::findOrFail($this->web->template_id);
        $page = $template->pages()->whereJsonContains('data->setting->is_new_page_template', true)->first();
        if (empty($page)) {
            $page = $template->pages()->first();
        }
        if ($page) {
            $this->footer = $page->footer;
            $this->header = $page->header;
            $this->content = $page->content;
            $this->mainCss = $page->mainCss;
            $this->sectionCss = $page->sectionCss;
            $this->css = $page->css;
            $this->script = $page->script;
            $this->data = $page->data;
            $this->save();

            /**
             * New page should include only header and footer 23/10/13
             */
            $this->addSections($page->sections, false);
        }
    }
}
