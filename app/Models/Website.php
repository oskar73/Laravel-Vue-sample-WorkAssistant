<?php

namespace App\Models;

use App\Enums\ModuleEnum;
use App\Models\Builder\Template;
use App\Models\Website\Page;
use App\Models\Website\Page as WebsitePage;
use App\Models\Website\PageSection;
use App\Repositories\SectionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations as Relations;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Yajra\DataTables\DataTables;
use Log;

class Website extends SiteBaseModel implements HasMedia
{
    use InteractsWithMedia;
    use HasApiTokens;

    protected $connection = 'mysql2';
    protected $table = 'websites';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['image', 'favicon', 'logo', 'webUrl'];

    protected $casts = [
        'data' => 'json',
    ];

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

    public function getWebUrlAttribute(): string
    {
        $protocol = 'https://';

        return $protocol . $this->domain;
    }

    public function primaryDomain()
    {
        if ($this->domain_type === 'subdomain') {
            return $this->hasOne(DomainCustom::class, 'web_id')->withDefault();
        } elseif ($this->domain_type === 'connected') {
            return $this->hasOne(DomainConnect::class, 'web_id')->withDefault();
        } else {
            return $this->hasOne(Domain::class, 'web_id')->withDefault();
        }
    }

    public function owner()
    {
        return $this->hasOne(WebsiteUser::class, 'web_id')->where('is_owner', 1)->withDefault();
    }

    public function modules()
    {
        return $this->hasMany(WebsiteModule::class, 'web_id')
            ->where("status", 1);
    }

    public function activeModules()
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

    public function storeRule($request)
    {
        if ($request->domain_type === 'subdomain') {
            $rule['subdomain'] = 'required|min:4|unique:domain_customs,subdomain';
        } elseif ($request->domain_type === 'connected') {
            $rule['connected_domain'] = 'required|exists:domain_connects,id,web_id,NULL';
        } else {
            $rule['hosted_domain'] = 'required|exists:domains,id,web_id,NULL';
        }
        $rule['domain_type'] = 'in:subdomain,connected,hosted';
        if (!$request->credentials) {
            $rule['email'] = ' required|email';
            $rule['password'] = ' required|min:8';
        }
        $rule['owner'] = 'required|exists:users,id';
        $rule['status'] = 'required|in:active,pending';
        $rule['status_by_owner'] = 'required|in:active,pending,maintenance';
        $rule['storage'] = 'required';
        $rule['page'] = 'required|integer';
        $rule['module_limit'] = 'required|integer';
        $rule['featured_module_limit'] = 'required|integer';
        // $rule['template'] = 'required';
        $rule['name'] = 'max:191';

        return $rule;
    }

    public function storeUserRule($request)
    {
        if ($request->domain_type === 'subdomain') {
            $rule['subdomain'] = 'required|min:4|unique:domain_customs,subdomain';
        } elseif ($request->domain_type === 'connected') {
            $rule['connected_domain'] = 'required|exists:domain_connects,name,web_id,NULL';
        } else {
            $rule['hosted_domain'] = 'required|exists:domains,name,web_id,NULL';
        }
        $rule['domain_type'] = 'in:subdomain,connected,hosted';
        if ($request->credentials == 0) {
            $rule['email'] = ' required|email';
            $rule['password'] = ' required|min:8';
        }
        // $rule['template'] = 'required';
        $rule['status'] = 'required|in:active,pending,maintenance';
        $rule['name'] = 'max:191';

        return $rule;
    }

    public function setPrimaryRule($request)
    {
        $rule['domain_type'] = 'required|in:subdomain,connected,hosted';
        if ($request->domain_type == 'subdomain') {
            $rule['id'] = 'required|exists:domain_customs,id,web_id,NULL';
        } elseif ($request->domain_type == 'connected') {
            $rule['id'] = 'required|exists:domain_connects,id,web_id,NULL';
        } else {
            $rule['id'] = 'required|exists:domains,id,web_id,NULL';
        }

        return $rule;
    }

    public function parkRule()
    {
        $rule['domain'] = 'required|regex:/(?=^.{4,253}$)(^((?!-)[a-zA-Z0-9-]{1,63}(?<!-)\.)+[a-zA-Z]{2,63}$)/|unique:domain_connects,name,null,null,pointed,1';

        return $rule;
    }

    public function storeWebsite($request, $template)
    {
        $website = new Website();
        $website->user_id = $request->owner;

        $ssh = optional(option('ssh'));

        if ($request->domain_type === 'subdomain') {
            $website->domain = $request->subdomain . "." . $ssh['root_domain'];
            $website->domain_type = 'subdomain';
        } elseif ($request->domain_type === 'connected') {
            $domain = DomainConnect::find($request->connected_domain);
            $website->domain = $domain->name;
            $website->domain_type = 'connected';
        } elseif ($request->domain_type === 'hosted') {
            $domain = Domain::find($request->hosted_domain);
            $website->domain = $domain->name;
            $website->domain_type = 'hosted';
        }

        $website->status = $request->status;
        $website->status_by_owner = $request->status_by_owner;
        $website->name = $request->name;
        $website->css = $template?->css;
        $website->script = $template?->script;
        $website->storage_limit = $request->storage;
        $website->page_limit = $request->page;
        $website->module_limit = $request->module_limit;
        $website->fmodule_limit = $request->featured_module_limit;
        $website->data = ['name' => $request->name];
        $website->save();

        if ($request->domain_type === 'subdomain') {
            DomainCustom::create([
                'web_id' => $website->id,
                'user_id' => user()->id,
                'name' => $request->subdomain . "." . $ssh['root_domain'],
                'subdomain' => $request->subdomain,
                'pointed' => 1,
            ]);
        } elseif ($request->domain_type === 'connected') {
            $domain->web_id = $website->id;
            $domain->save();
        } elseif ($request->domain_type === 'hosted') {
            $domain->web_id = $website->id;
            $domain->save();
        }

        return $website;
    }

    /**
     *  Generate a new website when a user wants to launch his own website with a template or blank.
     *  If he selected a template, his website will take the properties of the template he selected as default besides only the website name.
     */
    public function storeUserWebsite($request, $package, $modules)
    {
        return DB::connection('mysql2')->transaction(function () use ($request, $package, $modules) {
            // Check if the request comes with template.
            if ($request->template) {
                $template = Template::whereId($request->template)->where("status", 1)->firstorfail();
            }

            $website = new Website();

            // Assign user and package he is going with.
            $website->user_id = user()->id;
            $website->package_id = $package->id;

            $ssh = optional(option('ssh'));

            // Assign domain requested to the website
            if ($request->domain_type === 'subdomain') {
                $website->domain = $request->subdomain . "." . $ssh['root_domain'];
                $website->domain_type = 'subdomain';
            } elseif ($request->domain_type === 'connected') {
                $domain = DomainConnect::whereName($request->connected_domain)->firstorfail();
                $website->domain = $domain->name;
                $website->domain_type = 'connected';
            } elseif ($request->domain_type === 'hosted') {
                $domain = Domain::whereName($request->hosted_domain)->firstorfail();
                $website->domain = $domain->name;
                $website->domain_type = 'hosted';
            }

            // Basic settings
            $website->name = $request->name;
            $website->storage_limit = $package->storage;
            $website->module_limit = $package->module;
            $website->fmodule_limit = $package->featured_module;
            $website->page_limit = $package->page;
            $website->status = "active";
            $website->status_by_owner = $request->status;

            // Design settings from template
            if (isset($template)) {
                $website->css = $template->css;
                $website->script = $template->script;
                $data = $template?->data;
                if ($data) {
                    $data['name'] = $request->name;
                } else {
                    $data = ['name' => $request->name];
                }
                $website->data = $data;
                $website->template_id = $template->id;
            } else {
                $defaultData = config('builder.default');
                $defaultData['name'] = $request->name;
                $website->data = $defaultData;
            }

            // Save website
            $website->save();

            $websiteData = [
                'website_id' => $website->id,
                'website_domain' => $website->domain,
                'message' => "I created a website. you can see the link here: {$website->domain}",
            ];

            (new \App\Models\FeedListing)->storeFeedData($websiteData);

            if ($request->domain_type === 'subdomain') {
                DomainCustom::create([
                    'web_id' => $website->id,
                    'user_id' => user()->id,
                    'name' => $request->subdomain . "." . $ssh['root_domain'],
                    'subdomain' => $request->subdomain,
                    'pointed' => 1,
                ]);
            } else {
                $domain->web_id = $website->id;
                $domain->save();
            }

            // Ignore website count for admin type user
            if (!auth()->user()->hasRole('admin')) {
                $package->current_website += 1;
                $package->save();
            }

            // Create Website Modules he chose. The module will be unpublished as default.
            $website->createModules($modules);

            // If the requested template exists, website will have copy of the pages from the website. Otherwise, we will have to create a just home page for both work & published version.
            // Work version is just used for Edit purpose, Published Version is use for published content which means you can really see by visiting your website.
            // Edit version will not be available on the published website.
            $website->copyPagesFromTemplate($template ?? null);

            $website->createAdmin($request, user()->id);

            // We save the creation steps on the progress. Delete that progress once the website launched with it.
            if ($request->progress) {
                $progress = PackageWebsiteProgress::whereId($request->progress)
                    ->where("package_id", $package->id)
                    ->first();
                $progress?->delete();
            }

            return $website;
        });
    }

    public function createModules($modules)
    {
        foreach ($modules as $module) {
            $web_module = new WebsiteModule();
            $web_module->web_id = $this->id;
            $web_module->slug = $module->slug;
            $web_module->publish = 0;
            $web_module->name = $module->name;
            $web_module->featured = $module->featured;
            $web_module->status = 1;
            $web_module->save();
        }

        return $this;
    }

    public function copyPagesFromTemplate($template)
    {
        if (($template ?? null) && count($template->pages)) {
            foreach ($template->pages as $page) {
                if (isset($page->data['setting']['is_new_page_template']) && $page->data['setting']['is_new_page_template']) {
                    continue;
                }
                $publishedPage = new WebsitePage();
                $publishedPage->web_id = $this->id;
                $publishedPage->parent_id = $page->parent_id;
                $publishedPage->name = $page->name;
                $publishedPage->type = $page->type;
                $publishedPage->url = $page->url;
                $publishedPage->order = $page->order;
                $publishedPage->footer = $page->footer;
                $publishedPage->header = $page->header;
                $publishedPage->footer_order = $page->footer_order;
                $publishedPage->content = $page->content;
                $publishedPage->mainCss = $page->mainCss;
                $publishedPage->sectionCss = $page->sectionCss;
                $publishedPage->css = $page->css;
                $publishedPage->script = $page->script;
                $publishedPage->status = 1;
                $publishedPage->data = $page->data;
                $publishedPage->save();
                $publishedPage->addSections($page->sections);

                $unpublishedPage = $publishedPage->replicate();
                $unpublishedPage->status = 0;
                $unpublishedPage->save();
                $unpublishedPage->addSections($page->sections);
            }
        } else {
            // create a page for blank template

            // Published Page
            $publishedPage = new WebsitePage();
            $publishedPage->web_id = $this->id;
            $publishedPage->name = 'Home';
            $publishedPage->data = ['title' => 'Home Page'];
            $publishedPage->save();

            // Unpublished Page
            $unpublishedPage = $publishedPage->replicate();
            $unpublishedPage->status = 0;
            $unpublishedPage->save();
        }
    }

    public function createPages($pages)
    {
        $pageWebArray = [];

        foreach ($pages as $page) {
            $newPage = new WebsitePage();
            $newPage->web_id = $this->id;
            $newPage->parent_id = $page->parent_id;
            $newPage->name = $page->name;
            $newPage->type = $page->type;
            $newPage->url = $page->url;
            $newPage->order = $page->order;
            $newPage->footer = $page->footer;
            $newPage->header = $page->header;
            $newPage->footer_order = $page->footer_order;
            $newPage->content = $page->content;
            $newPage->mainCss = $page->mainCss;
            $newPage->sectionCss = $page->sectionCss;
            $newPage->css = $page->css;
            $newPage->script = $page->script;
            $newPage->status = 0;
            $newPage->data = json_encode($page->data);
            $newPage->save();
            $newPage->addSections($page->sections);
            $pageWebArray[$page->id] = $newPage->id;
        }

        foreach ($pageWebArray as $key => $pageId) {
            $page = WebsitePage::find($pageId);
            $page->parent_id = $page->parent_id === 0 ? 0 : $pageWebArray[$page->parent_id];
            $page->save();
        }
    }

    public function createAdmin($request, $user_id)
    {
        $user = User::find($user_id);
        $admin = new WebsiteUser();
        $admin->name = $user->name;
        $admin->web_id = $this->id;
        $admin->email_verified_at = Carbon::now()->toDateTimeString();
        $admin->is_owner = 1;

        if ($request->credentials == 1) {
            $admin->email = $user->email;
            $admin->password = $user->password;
        } else {
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
        }
        $admin->save();

        DB::connection('mysql2')->table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => \App\Models\User::class,
            'model_id' => $admin->id,
        ]);
    }

    /**
     * @deprecated
     */
    public function header()
    {
        return $this->hasOne(\App\Models\WebsiteHeader::class, 'web_id');
    }

    /**
     * @deprecated
     */
    public function footer()
    {
        return $this->hasOne(\App\Models\WebsiteFooter::class, 'web_id');
    }

    public function pages(): Relations\HasMany
    {
        return $this->hasMany(WebsitePage::class, 'web_id')->with(['sections' => function ($query) {
            $query->with(['category' => function ($query) {
                $query->with('sections');
            }]);
        }]);
    }

    public function package()
    {
        return $this->belongsTo(UserPackage::class, 'package_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function storageUsage()
    {
        $limit = $this->storage_limit;
        $current = $this->current_storage;

        if ($limit == -1 || $limit == 0) {
            $percent = 0;
        } else {
            $percent = (float)$current / $limit * 100;
        }

        return "<div class=\"progress\" title='{$percent}%'>
                    <div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width: {$percent}%\" aria-valuenow=\"{$percent}\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>
                </div>
                <p class='mb-0'>{$current} / {$limit} GB</p>";
    }

    public function getDatatable($status, $user)
    {
        switch ($status) {
            case 'all':
                $websites = $this::with('user');

                break;
            case 'active':
                $websites = $this::with('user')->where('status', 'active');

                break;
            case 'inactive':
                $websites = $this::with('user')->where('status', 'inactive');

                break;
            case 'expired':
                $websites = $this::with('user')->whereIn('status', ['expired', 'canceled']);

                break;
        }
        if ($user != 'all') {
            $websites = $websites->where("user_id", $user);
        }

        return Datatables::of($websites)->addColumn('storage', function ($row) {
            return $row->storageUsage();
        })->addColumn('user', function ($row) {
            return "<a href='" . route('admin.userManage.detail', $row->user->id ?? '1') . "'>{$row->user->name}</a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->editColumn('status', function ($row) {
            if ($row->status == 'active') {
                $result = "<span class='c-badge c-badge-success'>Active</span>";
            } elseif ($row->status == 'pending') {
                $result = "<span class='c-badge c-badge-info'>Pending</span>";
            } else {
                $result = "<span class='c-badge c-badge-danger'>{$row->status}</span>";
            }

            return $result;
        })->editColumn('domain', function ($row) {
            return $row->domain . "<a href='http://" . $row->domain . "' target='_blank'> <i class='la la-external-link'></i></a>";
        })->editColumn('status_by_owner', function ($row) {
            if ($row->status_by_owner == 'active') {
                $result = "<span class='c-badge c-badge-success'>Active</span>";
            } elseif ($row->status_by_owner == 'pending') {
                $result = "<span class='c-badge c-badge-danger'>Pending</span>";
            } else {
                $result = "<span class='c-badge c-badge-info'>{$row->status_by_owner}</span>";
            }

            return $result;
        })->addColumn('action', function ($row) use ($user) {
            if ($user == 'all') {
                return '
                    <a href="' . route('admin.website.editContent', $row->id) . '" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Design</span>
                        </span>
                    </a>
                    <a href="' . route('admin.website.edit', $row->id) . '" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <button class="btn btn-outline-danger btn-sm m-1 p-2 m-btn m-btn--icon del-btn" data-id="' . $row->id . '" data-domain="' . $row->domain . '">
                        <span>
                            <i class="la la-remove"></i>
                            <span>Delete</span>
                        </span>
                    </button>';
            } else {
                return '
                    <a href="' . route('admin.website.editContent', $row->id) . '" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Design</span>
                        </span>
                    </a>
                    <a href="' . route('admin.website.edit', $row->id) . '" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>';
            }
        })->rawColumns(['domain', 'status', 'status_by_owner', 'user', 'storage', 'action'])->make(true);
    }

    public function saveWebsite($data): void
    {
        DB::connection('mysql2')->transaction(function () use ($data) {
            $newWebsite = $this->update([
                'name' => $data['name'],
                'template_id' => $data['template_id'],
                'data' => $data['data'],
            ]);

            (new Website\Page)->where('web_id', $data['id'])->delete();
            $this->saveWebsitePages($data['pages'], $data['id']);
        });
    }

    public function createWebsite($data): void
    {
        DB::connection('mysql2')->transaction(function () use ($data) {
            $newWebsite = $this->create($data);

            $this->saveWebsitePages($data['pages'], $newWebsite['id']);
        });
    }

    public function publishWebsite(): void
    {
        DB::connection('mysql2')->transaction(function () {
            Page::where('web_id', $this->id)->where('status', 1)->delete();
            $previewPages = Page::where('web_id', $this->id)->where('status', 0)->with('sections')->get();

            Log::info($previewPages);

            foreach ($previewPages as $page) {
                $page = $page->replicate();
                $page->push();
                $page->save();

                foreach ($page->sections as $section) {
                    $newSection = $section->replicate();
                    $newSection->page_id = $page->id;
                    $newSection->push();
                    $newSection->save();
                }

                $page->update(['status' => 1]);
            }

            // update website status
            $this->update([
                'status' => 'active',
                'status_by_owner' => 'active',
            ]);
        });
    }

    public function getEcommerceCategories()
    {
        return Module\EcommerceCategory::of($this->id)->status(1)->get();
    }

    public function getEcommerceProducts()
    {
        return Module\EcommerceProduct::of($this->id)->status(1)
            ->with('standardPrice')
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAppointmentCategories()
    {
        return Module\AppointmentCategory::of($this->id)->status(1)->get();
    }

    public function getPortfolioCategories()
    {
        return Module\PortfolioCategory::of($this->id)->status(1)->get();
    }

    public function getDirectoryCategories()
    {
        return Module\DirectoryCategory::of($this->id)->status(1)->get();
    }

    public function getDirectoryListings()
    {
        return Module\DirectoryListing::of($this->id)->status('approved')->get();
    }

    public function getPortfolioItems()
    {
        return Module\PortfolioItem::of($this->id)->status(1)->get();
    }

    public function getModules(): array
    {
        $websiteModules = $this->modules->pluck('slug')->toArray();
        $activeModules = $this->publicModules->pluck('slug')->toArray();

        Log::info($websiteModules, $activeModules);

        $modules = [
            'websiteModules' => $websiteModules,
            'activeModules' => $activeModules,
        ];
        if (count($websiteModules)) {
            if (in_array(ModuleEnum::SIMPLE_BLOG, $websiteModules)) {
                $blogCategories = Module\BlogCategory::of($this->id)->status(1)->get();
                $blogPosts = Module\BlogPost::of($this->id)->status('approved')->orderBy('featured', 'desc')->orderBy('created_at', 'desc')->get();

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


    public function getTemporaryUrl(string $slug)
    {
        if ($slug === 'add-product') {
            //            dd(\URL::temporarySignedRoute('home', now()->addMinutes(5), ['u' => $this->user->id]));
            return $this->web_url . '/admin/ecommerce/product/create';
        }

        abort(404);
    }

    // internal functions
    private function saveWebsitePages($pages, $webId): void
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
                'web_id' => $webId,
            ]);


            if (!empty($pageData['sections'])) {
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
