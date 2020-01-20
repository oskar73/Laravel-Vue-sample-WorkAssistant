<?php

namespace App\Models\Website;

use App\Enums\ModuleEnum;
use App\Models\Domain;
use App\Models\DomainConnect;
use App\Models\DomainCustom;
use App\Models\Module\BlogCategory;
use App\Models\Module\BlogPost;
use App\Models\SiteBaseModel;
use App\Models\Website\Page as WebsitePage;
use App\Models\WebsiteModule;
use App\Repositories\SectionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations as Relations;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\InteractsWithMedia;
use Throwable;

class UserTemplates extends SiteBaseModel implements HasMedia
{
    use InteractsWithMedia;
    use HasApiTokens;

    protected $connection = 'mysql2';
    protected $primaryKey = 'id';
    protected $table = 'user_templates';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['image', 'favicon', 'logo', 'webUrl'];

    protected $casts = [
        'data' => 'json',
    ];

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

    public function getWebUrlAttribute(): string
    {
        $protocol = 'https://';

        return $protocol . $this->domain;
    }

    public function modules()
    {
        return $this->hasMany(WebsiteModule::class, 'web_id')
            ->where("status", 1);
    }

    public function publicModules()
    {
        return $this->hasMany(WebsiteModule::class, 'web_id')
            ->where("status", 1)
            ->where('publish', 1);
    }

    public function getModules(): array
    {
        $websiteModules = $this->modules->pluck('slug')->toArray();
        $activeModules = $this->publicModules->pluck('slug')->toArray();

        $modules = [
            'websiteModules' => $websiteModules,
            'activeModules' => $activeModules,
        ];
        if (count($websiteModules)) {
            if (in_array(ModuleEnum::SIMPLE_BLOG, $websiteModules)) {
                $blogCategories = BlogCategory::of($this->id)->status(1)->get();
                $blogPosts = BlogPost::of($this->id)->status('approved')->orderBy('featured', 'desc')->orderBy('created_at', 'desc')->get();

                $modules['blog'] = [
                    'categories' => $blogCategories ?? [],
                    'posts' => $blogPosts ?? [],
                ];
            }

            if (in_array(ModuleEnum::ECOMMERCE, $websiteModules)) {
                $productCategories = $this->getEcommerceCategories();
                $products = $this->getEcommerceProducts();

                $modules['ecommerce'] = [
                    'categories' => $productCategories ?? [],
                    'products' => $products ?? [],
                ];
            }

            if (in_array(ModuleEnum::DIRECTORY, $websiteModules)) {
                $directoryCategories = $this->getDirectoryCategories();
                $listings = $this->getDirectoryListings();

                $modules['directory'] = [
                    'categories' => $directoryCategories ?? [],
                    'listings' => $listings ?? [],
                ];
            }

            if (in_array(ModuleEnum::APPOINTMENT, $websiteModules)) {
                $appointmentCategories = $this->getAppointmentCategories();

                $modules['appointment'] = [
                    'categories' => $appointmentCategories ?? [],
                ];
            }

            if (in_array(ModuleEnum::PORTFOLIO, $websiteModules)) {
                $portfolioCategories = $this->getPortfolioCategories();
                $items = $this->getPortfolioItems();

                $modules['portfolio'] = [
                    'categories' => $portfolioCategories ?? [],
                    'items' => $items,
                ];
            }

            $moduleCategories = app(SectionRepository::class)->getModuleSectionCategories($websiteModules);

            $modules['moduleCategories'] = $moduleCategories;
        }

        return $modules;
    }

    public function primaryDomain()
    {
        if ($this->domain_type === 'subdomain') {
            return $this->hasOne(DomainCustom::class, 'web_id', 'web_id')->withDefault();
        } elseif ($this->domain_type === 'connected') {
            return $this->hasOne(DomainConnect::class, 'web_id', 'web_id')->withDefault();
        } else {
            return $this->hasOne(Domain::class, 'web_id', 'web_id')->withDefault();
        }
    }

    public function activeModules(): Relations\HasMany
    {
        return $this->hasMany(WebsiteModule::class, 'web_id', 'web_id')
            ->where("status", 1);
    }

    /**
     * @throws Throwable
     */
    public function createUserTemplate($data): void
    {
        DB::connection('mysql2')->transaction(function () use ($data) {
            $newTemplate = $this->create($data);
            $newPages = $data['pages'];
            foreach ($newPages as $key => $page) {
                $newPages[$key]['user_template_id'] = $newTemplate['id'];
            }
            $this->savePages($newPages);
        });
    }

    public function pages(): Relations\HasMany
    {
        return $this->hasMany(WebsitePage::class, 'user_template_id')->with(['sections' => function ($query) {
            // return $this->hasMany(WebsitePage::class, 'web_id', 'web_id')->with(['sections' => function ($query) {
            $query->with(['category' => function ($query) {
                $query->with('sections');
            }]);
        }]);
    }

    private function savePages($pages): void
    {
        foreach ($pages as $key => $pageData) {
            $newPage = Page::create([
                'name' => $pageData['name'],
                'url' => $pageData['url'],
                'css' => $pageData['css'],
                'script' => $pageData['script'],
                'status' => 0,
                'order' => $key,
                'data' => $pageData['data'],
                'user_template_id' => $pageData['user_template_id'],
            ]);


            if (! empty($pageData['sections'])) {
                $newSections = [];
                foreach ($pageData['sections'] as $section) {
                    $newSections[] = [
                        'page_id' => $newPage['id'],
                        'name' => $section['name'],
                        'category_id' => $section['category_id'],
                        'data' => json_encode($section['data']),
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];
                }

                PageSection::insert($newSections);
            }
        }
    }
}
