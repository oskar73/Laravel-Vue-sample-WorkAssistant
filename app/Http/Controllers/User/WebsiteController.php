<?php

namespace App\Http\Controllers\User;

use App\Enums\ModuleEnum;
use App\Integration\Paypal;
use App\Models\AppointmentCategory;
use App\Models\BlogPost;
use App\Models\Builder\SectionCategory;
use App\Models\Builder\Template;
use App\Models\Builder\TemplateCategory;
use App\Models\Builder\TemplateFooter;
use App\Models\Builder\TemplateHeader;
use App\Models\Country;
use App\Models\Domain;
use App\Models\DomainConnect;
use App\Models\DomainCustom;
use App\Models\Module;
use App\Models\ModuleCategory;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageWebsiteProgress;
use App\Models\PaypalAccount;
use App\Models\StripeAccount;
use App\Models\Theme;
use App\Models\ThemeCategory;
use App\Models\UserPackage;
use App\Models\Website;
use App\Models\Website\Page as WebsitePage;
use App\Models\Website\PageSection;
use App\Models\Website\UserTemplates;
use App\Models\WebsiteFooter;
use App\Models\WebsiteHeader;
use App\Models\WebsiteModule;
use App\Models\WebsiteUser;
use App\Repositories\PaletteRepository;
use App\Repositories\SectionRepository;
use App\Repositories\TemplateRepository;
use App\Rules\FQDN;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends UserController
{
    private PaletteRepository $paletteRepository;
    private SectionRepository $sectionRepository;
    private TemplateRepository $templateRepository;

    public function __construct(Website $model, PaletteRepository $paletteRepository, SectionRepository $sectionRepository, TemplateRepository $templateRepository)
    {
        $this->model = $model;
        $this->paletteRepository = $paletteRepository;
        $this->sectionRepository = $sectionRepository;
        $this->templateRepository = $templateRepository;
    }

    public function gettingStarted(Request $request)
    {
        $userPackages = UserPackage::my()
            ->with("websites", "progresses")
            ->status("active")
            ->get();

        $packages = [];
        foreach ($userPackages as $userPackage) {
            //            $possibleCount = $userPackage->website - $userPackage->websites->count();
            $possibleCount = $userPackage->website - $userPackage->current_website;
            if ($userPackage->website == -1 || $possibleCount > 0) {
                $userPackage->newCount = $possibleCount - $userPackage->progresses->count();
                array_push($packages, $userPackage);
            }
        }

        $categories = $this->templateRepository->getCategories();
        $moduleArray = Module::status(1)->get(["id", "slug", "name", "featured"])->toArray();
        $moduleJson = json_encode($moduleArray);
        $module_wishes = session("module_wishes", []);

        $template = $request->session()->get('selected_template');

        return view("user.website.started", compact("packages", "categories", "moduleJson", "module_wishes", "template"));
    }

    public function resume($id)
    {
        $package = UserPackage::my()
            ->where('id', $id)
            ->with("websites", "progresses", "meetings", "orderItem")
            ->status("active")
            ->firstorfail();

        if ($package->meetings->count() && !user()->appointments->count()) {
            $meetings = $package->meetings;
            $categories = AppointmentCategory::where("status", 1)->get();
            $isWebsite = true;

            return view("user.appointment.create", compact("meetings", "categories", "isWebsite"));
        }

        $progress = $package->progresses->first();

        $templateCategories = $this->templateRepository->getCategories();
        $moduleCategories = ModuleCategory::select('id', 'slug', 'name', 'parent_id')
            ->with('approvedSubCategories')
            ->isParent()
            ->status(1)
            ->orderBy('order')
            ->get();
        $moduleArray = Module::status(1)->get(["id", "slug", "name", "featured"])->toArray();
        $moduleJson = json_encode($moduleArray);
        $modules = json_decode($package->modules);
        Session::put("module_wishes", $modules);
        $module_wishes = session("module_wishes");
        $template = null;
        if ($progress && $progress->data['template']) {
            $template = Template::find($progress->data['template']);
        }

        $originalPackage = $package->getOriginalPackage();
        $module_recommended = $originalPackage ? $originalPackage->modules->pluck('slug')->toArray() : [];

        return view("user.website.resume", compact("package", "progress", "templateCategories", "moduleCategories", "moduleJson", "module_wishes", "template", "module_recommended"));
    }

    public function saveStep(Request $request)
    {
        try {
            $userPackage = UserPackage::where("user_id", user()->id)
                ->with("websites")
                ->where("status", "active")
                ->whereId($request->package)
                ->firstorfail();

            if ($request->progress) {
                $progress = PackageWebsiteProgress::whereId($request->progress)
                    ->where("package_id", $userPackage->id)
                    ->firstorfail();
                $data = $progress->data;
            } else {
                $progress = new PackageWebsiteProgress();
                $progress->package_id = $userPackage->id;
            }
            $progress->step = $request->step;
            $data['name'] = $request->name;
            $data['template'] = $request->template;
            $data['header'] = $request->header;
            $data['footer'] = $request->footer;
            $data['domain_type'] = $request->domain_type;
            $data['domain'] = $request->domain;
            $data['subdomain'] = $request->subdomain;
            $data['modules'] = $request->modules;
            if ($request->modules) {
                $userPackage->modules = $request->modules;
                $userPackage->save();
            }
            $data['credentials'] = $request->credentials;
            $data['email'] = $request->email;
            $data['password'] = $request->password;
            $data['status'] = $request->status;
            $progress->data = $data;
            $progress->save();

            return $this->jsonSuccess($progress->id);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function templates(Request $request)
    {
        $temp = new Template();
        $result = $temp->filterTemplate($request, 'website');

        return response()->json($result);
    }

    public function getTemplates(Request $request)
    {
        try {
            $template = new Template();

            $selectedTemplate = null;
            if (session()->has('selected_template')) {
                $selectedTemplate = session()->get('selected_template');
            }

            $templates = $template->getTemplate($request, 12, $selectedTemplate->id ?? null);
            $view = view("components.user.websiteTemplate", compact("templates", "selectedTemplate"))->render();

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function previewTemplate(Request $request)
    {
        try {
            $template = Template::whereId($request->id)
                ->status(1)
                ->firstorfail(['header_id', 'id', 'footer_id', 'name', 'description', 'status', 'slug']);

            $view = view("components.user.websitePreview", compact("template"))->render();

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function getDomains()
    {
        try {
            $domains = Domain::where('user_id', user()->id)
                ->whereNull('web_id')
                ->orderBy('status')
                ->get(['name', 'user_id', 'pointed', 'status', 'expired_at', 'created_at', 'id']);

            $view = view("components.user.websiteDomain", compact("domains"))->render();

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function getModuleFeatures(Request $request)
    {
        try {
            $module = Module::with("media")->where("id", $request->id)
                ->where("status", 1)
                ->firstorfail();

            $view = view("components.user.websiteModuleFeature", compact("module"))->render();
            $data['view'] = $view;
            $data['feature'] = $module->description;

            return $this->jsonSuccess($data);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function index()
    {
        $activeWebsites = $this->model->with("package")
            ->where("user_id", user()->id)
            ->where("status", "active")
            ->latest()
            ->get();
        $pendingWebsites = $this->model->with("package")
            ->where("user_id", user()->id)
            ->where("status", "!=", "active")
            ->latest()
            ->get();

        return view(self::$viewDir . "website.index", compact("activeWebsites", "pendingWebsites"));
    }

    public function create()
    {
        $packages = UserPackage::where("user_id", user()->id)
            ->with("websites")
            ->where("status", "active")
            ->get();

        return view(self::$viewDir . "website.select", compact("packages"));
    }

    public function select($id)
    {
        $package = UserPackage::where("user_id", user()->id)
            ->whereId($id)
            ->firstorfail();

        if ($package->website != -1 && $package->website - $package->websites->count() == 0) {
            abort(404);
        }
        $templateModel = new Template();
        $templates = $templateModel->approvedAllTemplatesQuery()->status(1)
            ->select('id', 'name', 'slug', "header_id", "footer_id")
            ->with('media')
            ->get();

        $headers = TemplateHeader::status(1)->get(['id', 'name']);
        $footers = TemplateFooter::status(1)->get(['id', 'name']);

        $domains = Domain::where('user_id', user()->id)
            ->select('name', 'user_id', 'pointed', 'status', 'expired_at', 'created_at', 'id')
            ->whereNull('web_id')
            ->orderBy('status')
            ->get();

        $modules = Module::status(1)->get(['id', 'name', 'slug', 'description', 'new', 'featured']);

        return view(self::$viewDir . "website.create", compact("package", "templates", "domains", "headers", "footers", "modules"));
    }

    /**
     * Launches website
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request)
    {
        DB::beginTransaction();

        try {
            $package = UserPackage::my()
                ->where("id", $request->package)
                ->firstorfail();

            if ($package->remain_website < 1) {
                return $this->jsonError(["Your package already has full numbers of websites."]);
            }

            $module_slugs = json_decode($request->modules);

            $modules = Module::whereIn("slug", $module_slugs)
                ->where("status", 1)
                ->get();

            $module_counts = $modules->count();
            $featured_counts = $modules->where("featured", 1)->count();

            if ($package->module != -1 && ($package->module - $module_counts) < 0) {
                return $this->jsonError(['Your max module limit count is ' . $package->module]);
            }
            if ($package->featured_module != -1 && ($package->featured_module - $featured_counts) < 0) {
                return $this->jsonError(['Your max featured module limit count is ' . $package->featured_module]);
            }

            // Validation
            $validation = Validator::make($request->all(), $this->model->storeUserRule($request));

            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            // Store website
            $website = $this->model->storeUserWebsite($request, $package, $modules);

            $website->createToken('default');

            // Send an initial http request to the website to generate SSL.
            try {
                \Http::get("//{$website->domain}");
            } catch (\Exception $e) {
                \Log::info(json_encode($e->getMessage()));
            }

            DB::commit();

            $view = view("user.website.launch", compact("website"))->render();

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->jsonExceptionError($e);
        }
    }

    public function store(Request $request, $id)
    {
        try {
            $package = UserPackage::where("user_id", user()->id)
                ->whereId($id)
                ->firstorfail();

            if ($package->website != -1 && $package->website - $package->websites->count() == 0) {
                return $this->jsonError(["Your package already has full numbers of websites."]);
            }

            $module_ids = $request->modules;

            $modules = Module::whereIn("id", $module_ids)->where("status", 1)->get();

            $module_counts = $modules->count();
            $featured_counts = $modules->where("featured", 1)->count();

            if ($package->module != -1 && ($package->module - $module_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max module limit count is ' . $package->module]]);
            }
            if ($package->featured_module != -1 && ($package->featured_module - $featured_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max featured module limit count is ' . $package->featured_module]]);
            }

            $validation = Validator::make($request->all(), $this->model->storeUserRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $template = Template::whereId($request->template)->where("status", 1)->firstorfail();
            $c_header = TemplateHeader::status(1)->whereId($request->header)->first();
            $c_footer = TemplateFooter::status(1)->whereId($request->footer)->first();

            $website = $this->model->storeUserWebsite($request, $template, $package);

            try {
                \Http::get("//{$website->domain}");
            } catch (\Exception $e) {
                \Log::info(json_encode($e->getMessage()));
            }

            $header = new WebsiteHeader();
            $header->create([
                'web_id' => $website->id,
                'content' => $c_header->content ?? '',
                'css' => $c_header->css ?? '',
                'script' => $c_header->script ?? '',
            ]);

            $footer = new WebsiteFooter();
            $footer->create([
                'web_id' => $website->id,
                'content' => $c_footer->content ?? '',
                'css' => $c_footer->css ?? '',
                'script' => $c_footer->script ?? '',
                'mainCss' => $c_footer->mainCss ?? '',
                'sectionCss' => $c_footer->sectionCss ?? '',
            ]);

            $website->createModules($modules);

            $website->createPages($template->approvedPages);

            $website->createAdmin($request, user()->id);

            return $this->jsonSuccess($website);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function edit($id)
    {
        $website = Website::with("activeModules")
            ->my()
            ->whereId($id)
            ->firstorfail();

        if ($website->status != 'active') {
            return back()->with("error", "Sorry, website package is expired or website is banned");
        }
        $modules = Module::where("status", 1)->get(['name', 'slug', 'featured', 'id']);

        return view(self::$viewDir . "website.edit", compact("website", "modules"));
    }

    /**
     * @param Request $request
     * @param $id // Website Id
     * @return JsonResponse
     * Update Website data by section builder
     */
    public function updateData(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $website = Website::find($id);
            $website->update($data);

            $logo = $data['logo'];
            $favicon = $data['favicon'];

            // update logo
            if ($logo && strpos($logo, 'base64') > 0) {
                $website->clearMediaCollection('logo')
                    ->addMediaFromBase64($logo)
                    ->storingConversionsOnDisk('s3-pub-bizinasite')
                    ->usingFileName(guid() . ".png")
                    ->toMediaCollection('logo', 's3-pub-bizinasite');
            }

            // update favicon
            if ($favicon && strpos($favicon, 'base64') > 0) {
                $website->clearMediaCollection('favicon')
                    ->addMediaFromBase64($favicon)
                    ->storingConversionsOnDisk('s3-pub-bizinasite')
                    ->usingFileName(guid() . ".png")
                    ->toMediaCollection('favicon', 's3-pub-bizinasite');
            }

            // update website pages
            $pages = $data['pages'];
            if ($pages && is_array($pages)) {
                foreach ($pages as $page) {
                    if ($page['id']) {
                        $websitePage = WebsitePage::find($page['id']);
                        $websitePage->update($page);
                    } else {
                        $page['web_id'] = $id;
                        $websitePage = WebsitePage::create($page);
                    }

                    // delete old sections
                    $websitePage->sections->each(function ($section) {
                        $section->delete();
                    });

                    // add new sections
                    foreach ($page['sections'] as $section) {
                        $newSection = new PageSection();
                        $newSection->page_id = $websitePage->id;
                        $newSection->name = $section['name'];
                        $newSection->category_id = $section['category_id'];
                        $newSection->data = $section['data'];
                        $newSection->save();
                    }
                }
            }

            return $this->jsonSuccess([
                'logo' => $website->logo,
                'favicon' => $website->favicon,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updatePage(Request $request, $id): JsonResponse
    {
        //dd($request->all());
        try {
            $page = WebsitePage::find($id);
            if ($page) {
                if ($page->type != 'module') {
                    $page->savePage($request);
                }
            } else {
                $page = new WebsitePage();
                $page->savePage($request);
            }

            return $this->jsonSuccess($page);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception->getMessage());
        }
    }

    /**
     * @param $id // page Id
     */
    public function duplicatePage($id): JsonResponse
    {
        try {
            $newPage = WebsitePage::find($id);
            $newPage = $newPage->replicatePage();

            return $this->jsonSuccess($newPage);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePagesOrder(Request $request): JsonResponse
    {
        if (WebsitePage::updateOrder($request)) {
            return $this->jsonSuccess(["success" => $request->all()]);
        }

        return $this->jsonError();
    }

    public function editContent(Request $request, Website $website, $templateId = null)
    {
        if ($request->query('templateId')) {
            $userTemplate = (new \App\Models\Website\UserTemplates)->where('id', $request->query('templateId'))->with(['pages' => function ($query) {
                $query->where('status', 0);
            }])->firstOrFail();

            return view("user.website.editContent", [
                'basePath' => '/account/website/editContent/' . $userTemplate->web_id,
                'template' => $userTemplate,
            ]);
        } else {
            $website->load(['pages' => function ($query) {
                $query->where('status', 0);
            }]);

            return view("user.website.editContent", [
                'basePath' => '/account/website/editContent/' . $website->id,
                'template' => $website,
            ]);
        }
    }

    public function preview(Request $request, $id, $url = null): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        if ($request->query('templateId')) {
            $userTemplate = (new \App\Models\Website\UserTemplates)->where('id', $request->query('templateId'))->with(['pages' => function ($query) {
                $query->where('status', 0);
            }])->firstOrFail();

            $website = Website::where('id', $id)
                ->with(['pages' => function ($query) {
                    $query->where('status', 0)->orderBy('order')->with('sections');
                }])->first();

            if (empty($userTemplate)) {
                abort(404, 'Website does not exists or inactive.');
            }

            $userTemplate->id = $id;

            $data = [
                'basePath' => 'account/website/preview/' . $id,
                'template' => $userTemplate,
                'modules' => $website->getModules(),
            ];
        } else {
            $website = Website::where('id', $id)
                ->with(['pages' => function ($query) {
                    $query->where('status', 0)->orderBy('order')->with('sections');
                }])->first();

            if (empty($website)) {
                abort(404, 'Website does not exists or inactive.');
            }

            $data = [
                'basePath' => 'account/website/preview/' . $id,
                'template' => $website,
                'modules' => $website->getModules(),
            ];
        }

        return view("preview.website", $data);
    }

    public function getWebsiteData(Request $request, $id)
    {
        try {
            // dd("website");die();
            if ($request->query('templateId')) {
                $website = UserTemplates::findOrFail($request->query('templateId'));
            } else {
                $website = Website::findOrFail($id);
            }
            $template = Template::whereId($website->template_id)->first();
            if (!$template) {
                $template = Template::where('status', 1)->first();
            }
            $website->category_id = $template->category_id;

            $allCategories = SectionCategory::select(['id', 'name', 'slug', 'recommended', 'module', 'limit_per_page'])
                ->with('sections:category_id,name,data')
                ->where('status', 1)
                ->orderBy('order')
                ->get();

            $systemPalettes = $this->paletteRepository->getAdminPalettes();
            $userPalettes = $this->paletteRepository->getUserPalettes();

            $themeCategories = ThemeCategory::where(function ($q) {
                $q->where('user_id', auth()->user()->id)->orWhereNull('user_id');
            })
                ->where('status', 1)
                ->get(['id', 'name', 'user_id']);

            $themes = Theme::where('status', 1)
                ->where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereNull('user_id');
                })
                ->get();

            $countries = Country::all();

            $templateCategories = TemplateCategory::with([
                'templates' => function ($q) {
                    $q->select(['category_id', 'name', 'id', 'featured', 'new'])->where('status', 1);
                },
            ])->latest()->get();

            $modules = $website->getModules();

            return $this->jsonSuccess([
                'templateCategories' => $templateCategories,
                'allCategories' => $allCategories,
                'systemPalettes' => $systemPalettes,
                'userPalettes' => $userPalettes,
                'themeCategories' => $themeCategories,
                'themes' => $themes,
                'countries' => $countries,
                'modules' => $modules ?? [],
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function deletePage(Request $request): JsonResponse
    {
        try {
            $id = $request->pageId;
            WebsitePage::destroy($id);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function domainKeyUp(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'subdomain' => 'required|string|min:4|unique:domain_customs,subdomain',
            'domain' => new FQDN(),
        ]);
        if ($validation->fails()) {
            return $this->jsonError($validation->errors());
        }

        return $this->jsonSuccess($request->all());
    }

    public function getDomain($id)
    {
        $website = Website::my()->whereId($id)
            ->where("status", "active")
            ->firstorfail();
        $view = view("components.account.websiteDomain", compact('website'))->render();

        return $this->jsonSuccess($view);
    }

    public function updateDomain(Request $request, $id)
    {
        try {
            $website = Website::my()->whereId($id)
                ->where("status", "active")
                ->firstorfail();

            $validation = Validator::make($request->all(), [
                'subdomain' => 'required|string|min:4|max:191|unique:domain_customs,subdomain',
            ]);

            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $domain = DomainCustom::where("user_id", user()->id)
                ->whereId($request->id)
                ->firstorfail();

            $domain->subdomain = $request->subdomain;
            $domain->name = $request->subdomain . "." . optional(option("ssh"))['root_domain'];
            $domain->save();
            if ($website->domain_type == 'subdomain') {
                $website->domain = $domain->name;
                $website->save();
            }

            try {
                \Http::get("//{$website->domain}");
            } catch (\Exception $e) {
                \Log::info(json_encode($e->getMessage()));
            }

            return $this->jsonSuccess($domain);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateBasic(Request $request, $id)
    {
        try {
            $website = Website::my()->whereId($id)
                ->where("status", "active")
                ->firstorfail();

            $oldName = $website->name;

            $validation = Validator::make($request->all(), [
                'name' => 'max:191',
                'status' => 'required|in:active,pending,maintenance',
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            $website->name = $request->name;
            $website->status_by_owner = $request->status;
            $website->save();

            $websiteData = [
                'website_id' => $website->id,
                'website_domain' => $website->domain,
                'message' => "I changed the website name from $oldName to {$website->name}, here is a website link: {$website->domain}",
            ];
            (new \App\Models\FeedListing)->storeFeedData($websiteData);

            return $this->jsonSuccess($website);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateOwner(Request $request, $id)
    {
        try {
            $website = Website::my()->whereId($id)
                ->where("status", "active")
                ->firstorfail();

            $admin = $website->owner;

            $validation = Validator::make($request->all(), $admin->profileUpdateRule($id));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            $admin->email = $request->owner_email;
            if ($request->owner_password) {
                $admin->password = bcrypt($request->owner_password);
            }
            $admin->save();

            return $this->jsonSuccess($admin);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateModule(Request $request, $id)
    {
        try {
            $website = Website::my()->whereId($id)
                ->where("status", "active")
                ->firstorfail();
            if (isset($request->modules)) {
                $module_ids = $request->modules;
            } else {
                $module_ids = [];
            }

            $modules = Module::whereIn("id", $module_ids)->where("status", 1)->get();

            $module_counts = $modules->count();
            $featured_counts = $modules->where("featured", 1)->count();
            $module_limit = $website->module_limit;
            $fmodule_limit = $website->fmodule_limit;

            if ($module_limit != -1 && ($module_limit - $module_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max module limit count is ' . $module_limit]]);
            }
            if ($fmodule_limit != -1 && ($fmodule_limit - $featured_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max featured module limit count is ' . $fmodule_limit]]);
            }

            WebsiteModule::where("web_id", $website->id)
                ->whereNotIn("slug", $modules->pluck("slug")->toArray())
                ->get()
                ->each
                ->update(["status" => 0]);
            foreach ($modules as $module) {
                $web_module = WebsiteModule::where("web_id", $website->id)
                    ->where("slug", $module->slug)
                    ->first();
                if ($web_module == null) {
                    $web_module = new WebsiteModule();
                    $web_module->web_id = $website->id;
                    $web_module->slug = $module->slug;
                    $web_module->publish = 0;
                    $web_module->name = $module->name;
                    $web_module->featured = $module->featured;
                }
                $web_module->status = 1;
                $web_module->save();
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function activateModule(Request $request, $id)
    {
        try {
            $website = Website::my()->whereId($id)
                ->where("status", "active")
                ->firstorfail();
            if ($request->module === ModuleEnum::ECOMMERCE) {
                $gateway = [];
                $stripeAccount = StripeAccount::where('web_id', $website->id)->first();
                $stripeConnected = $stripeAccount && $stripeAccount->charges_enabled && $stripeAccount->payouts_enabled && $stripeAccount->details_submitted;
                if ($stripeConnected) {
                    array_push($gateway, 'stripe');
                }

                $paypalAccount = PaypalAccount::where('web_id', $website->id)->first();
                $paypalConnected = $paypalAccount && $paypalAccount->payments_receivable && $paypalAccount->primary_email_confirmed && $paypalAccount->permission_granted;
                if ($paypalConnected) {
                    array_push($gateway, 'paypal');
                }

                if (!count($gateway)) {
                    return response()->json([
                        'status' => 0,
                        'action' => 'payment',
                    ]);
                }
            }

            $module = WebsiteModule::where('web_id', $website->id)
                ->where("status", 1)
                ->where("slug", $request->module)
                ->firstorfail();
            $module->publish = 1;
            $module->save();

            // Generate pages for published module
            $pageModules = [
                ['slug' => ModuleEnum::SIMPLE_BLOG, 'url' => '/blog', 'name' => 'Blog'],
                ['slug' => ModuleEnum::ADVANCED_BLOG, 'url' => '/blog', 'name' => 'Blog'],
                ['slug' => ModuleEnum::MARKET_PLACE, 'url' => '/marketplace', 'name' => 'MarketPlace'],
                ['slug' => ModuleEnum::ECOMMERCE, 'url' => '/ecommerce'],
                ['slug' => ModuleEnum::DIRECTORY, 'url' => '/directory'],
                ['slug' => ModuleEnum::PORTFOLIO, 'url' => '/portfolio'],
            ];
            $moduleSlugs = array_column($pageModules, 'slug');
            $moduleIndex = array_search($module->slug, $moduleSlugs);

            // TODO: validation if page already exists
            if ($moduleIndex > -1) {
                $page = WebsitePage::where('web_id', $website->id)
                    ->where('name', $pageModules[$moduleIndex]['name'] ?? $module->name)
                    ->where('type', 'module')
                    ->where('module_name', $module->slug)
                    ->where('status', 1)
                    ->first();
                if (!$page) {
                    $page = WebsitePage::firstOrCreate([
                        'web_id' => $website->id,
                        'name' => $pageModules[$moduleIndex]['name'] ?? $module->name,
                        'type' => 'module',
                        'module_name' => $module->slug,
                        'url' => $pageModules[$moduleIndex]['url'],
                        'data' => [],
                    ]);
                }

                $p = WebsitePage::where('web_id', $website->id)
                    ->where('name', $pageModules[$moduleIndex]['name'] ?? $module->name)
                    ->where('type', 'module')
                    ->where('module_name', $module->slug)
                    ->where('status', 0)
                    ->first();
                if (!$p) {
                    WebsitePage::firstOrCreate([
                        'web_id' => $website->id,
                        'name' => $pageModules[$moduleIndex]['name'] ?? $module->name,
                        'type' => 'module',
                        'module_name' => $module->slug,
                        'url' => $pageModules[$moduleIndex]['url'],
                        'data' => [],
                        'status' => 0,
                    ]);
                }
            }

            return response()->json([
                'status' => 1,
                'data' => [
                    'module' => $module,
                    'page' => $page ?? null,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function setPrimary(Request $request, $id)
    {
        try {
            $website = Website::my()->whereId($id)
                ->where("status", "active")
                ->firstorfail();

            $validation = Validator::make($request->all(), $this->model->setPrimaryRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $domain_id = $request->id;
            $domain_type = $request->domain_type;
            if ($domain_type === 'hosted') {
                $domain = Domain::whereId($domain_id)->where("user_id", user()->id)->firstorfail();
            } elseif ($domain_type === 'connected') {
                $domain = DomainConnect::whereId($domain_id)->where("user_id", user()->id)->firstorfail();
            } elseif ($domain_type === 'subdomain') {
                $domain = DomainCustom::whereId($domain_id)->where("user_id", user()->id)->firstorfail();
            }
            $web = $website->primaryDomain;
            $web->web_id = null;
            $web->save();

            $domain->web_id = $website->id;
            $domain->save();

            $website->domain = $domain->name;
            $website->domain_type = $request->domain_type;
            $website->save();

            return $this->jsonSuccess($website);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function loadCustom()
    {
        try {
            $customs = DomainConnect::where('user_id', user()->id)
                ->whereNull('web_id')
                ->orderBy('pointed')
                ->get(['name', 'user_id', 'pointed', 'data', 'created_at', 'id']);

            $view = view('components.domain.custom', compact('customs'))->render();

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function checkSubDomain(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'subdomain' => [
                    'required',
                    'min:4',
                    'unique:domain_customs,subdomain',
                    'regex:/^[a-zA-Z0-9-]+$/',
                    'not_regex:/^-|-$|[^\w-]/',
                ],
            ]);
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function modules(Request $request)
    {
        $module = new Module();
        $result = $module->filterItem($request, 'website');

        return response()->json($result);
    }

    public function packages(Request $request)
    {
    }

    public function getModules(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $query = Module::status(1)->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%");
                $q->orwhere('description', 'LIKE', "%$keyword%");
            });
            if ($request->orderBy === 'featured') {
                $query = $query->orderBy('featured', 'DESC');
            } elseif ($request->orderBy === 'popular') {
                $query = $query->orderBy('featured', 'DESC');
            } else {
                $query = $query->orderBy('new', 'DESC');
            }
            $modules = $query->orderBy('order')->get(['id', 'name', 'slug', 'description', 'new', 'featured']);

            $view = view('components.user.websiteModule', compact('modules'))->render();

            $data['view'] = $view;
            $data['count'] = $modules->count();
            $data['modules'] = $modules;

            return $this->jsonSuccess($data);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    //    public function checkDns(Request $request)
    //    {
    //        try{
    //            $domain = DomainConnect::where('id', $request->id)
    //                ->where('pointed', 0)
    //                ->firstorfail();
    //
    //            //dns lookup
    //
    //            $domain->pointed = 1;
    //            $domain->save();
    //
    //            return response()->json([
    //                'status'=>1,
    //                'data'=>$domain
    //            ]);
    //        }catch(\Exception $e)
    //        {
    //            return response()->json([
    //                'status'=>0,
    //                'data'=>[json_encode($e->getMessage())]
    //            ]);
    //        }
    //    }
    public function connectDomain(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'domain' => 'required|unique:domain_connects,name|regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i',
            ]);
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $domain = new DomainConnect();
            $domain->user_id = user()->id;
            $domain->name = $request->domain;
            $domain->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deleteDomain(Request $request)
    {
        $id = $request->id;
        $webUsers = WebsiteUser::where('web_id', $id)->pluck('id')->toArray();
        Website::find($id)->delete();
        if (Domain::where('web_id', $id)) {
            Domain::where('web_id', $id)->delete();
        }
        if (DomainConnect::where('web_id', $id)) {
            DomainConnect::where('web_id', $id)->delete();
        }
        if (DomainCustom::where('web_id', $id)) {
            DomainCustom::where('web_id', $id)->delete();
        }
        if (WebsitePage::where('web_id', $id)) {
            WebsitePage::where('web_id', $id)->delete();
        }
        if (WebsiteFooter::where('web_id', $id)) {
            WebsiteFooter::where('web_id', $id)->delete();
        }
        if (WebsiteModule::where('web_id', $id)) {
            WebsiteModule::where('web_id', $id)->delete();
        }
        if (WebsitePage::where('web_id', $id)) {
            WebsitePage::where('web_id', $id)->delete();
        }
        if (WebsiteUser::where('web_id', $id)) {
            WebsiteUser::where('web_id', $id)->delete();
        }
        if (!empty($webUsers)) { //delete website user indivisual roles
            DB::connection('mysql2')->table('model_has_roles')
                ->where('model_type', \App\Models\User::class)
                ->whereIn('model_id', $webUsers)->delete();
        }

        return back();
    }

    public function contact(Request $request)
    {
        return $this->jsonSuccess($request);
    }

    public function getBlogs($limit = 0)
    {
        $blogPosts = BlogPost::where('is_published', 1)
            ->where('status', "approved")
            ->when($limit, function ($q) use ($limit) {
                $q->limit($limit);
            })
            ->orderBy('created_at', 'desc');
        $posts = [];

        if (!auth()->user()->hasRole('admin')) {
            $blogPosts->where('user_id', user()->id);
        }

        $blogPosts = $blogPosts->get();

        foreach ($blogPosts as $post) {
            $blogObj = (object)[
                'title' => $post->title,
                'body' => $post->body,
                'link' => '/blog/detail/' . $post->slug,
                'image' => (object)[
                    'src' => $post->getFirstMediaUrl('image', 'thumb'),
                ],
            ];

            $posts[] = $blogObj;
        }

        if (!$limit) {
            $posts[] = (object)[
                'title' => 'Create new post',
                'body' => '',
                'link' => '/account/blog/create',
                'image' => (object)[
                    'src' => asset('assets/img/logo_sm.png'),
                ],
            ];
        }

        return $posts;
    }

    public function addPage(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), []);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $page = new WebsitePage();
            $page = $page->createPage($request);

            $page = WebsitePage::with(['sections' => function ($query) {
                $query->with(['category' => function ($query) {
                    $query->with('sections');
                }]);
            }])->find($page->id);

            return $this->jsonSuccess($page);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function switchPackage(Request $request)
    {
        try {
            $userPackage = UserPackage::with('orderItem', 'orderItem.order')->findorfail($request->id);
            $targetPackage = Package::findorfail($request->target_package_id);
            $modules = $request->get('modules', []);

            $gateway = $userPackage->orderItem->order->gateway;
            if ($gateway === 'stripe') {

                $result = $this->handleStripeSwitchPackage($userPackage, $targetPackage, $modules);

                return $this->jsonSuccess([
                    'result' => $result,
                    'gateway' => $gateway,
                ]);
            } elseif ($gateway === 'paypal') {
                $result = $this->handlePaypalSwitchPackage($userPackage, $targetPackage, $modules);

                return $this->jsonSuccess([
                    'result' => $result,
                    'gateway' => $gateway,
                ]);
            }

            return $this->jsonExceptionError('Payment gateway not found');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    private function handleStripeSwitchPackage($userPackage, $targetPackage, $modules)
    {
        $orderItem = OrderItem::find($userPackage->orderItem->id);

        $stripe = option("stripe", null);
        $stripe_sk = optional($stripe)['secret'];

        $stripe = new \Stripe\StripeClient(
            $stripe_sk
        );

        $subscriptionId = $orderItem->agreement_id;

        $subscription = $stripe->subscriptions->retrieve($subscriptionId);
        if (!$subscription) {
            throw new \Exception('Subscription not found');
        }

        $updatedSubscription = $stripe->subscriptions->update($subscriptionId, [
            'items' => [
                [
                    'id' => $subscription->items->data[0]->id,
                    'price' => $targetPackage->prices->first()->plan_id,
                    'quantity' => 1,
                ],
            ],
        ]);

        $orderItem->agreement_id = $updatedSubscription->id;
        $orderItem->product_id = $targetPackage->id;
        $orderItem->sub_total = $targetPackage->prices->first()->price;
        $orderItem->product_detail = json_encode($targetPackage->toArray());
        $orderItem->price = json_encode($targetPackage->prices->first()->toArray());
        $orderItem->save();

        $userPackage->item = json_encode($targetPackage->toArray());
        $userPackage->modules = json_encode($modules);
        $userPackage->price = json_encode($targetPackage->prices->first()->toArray());
        $userPackage->website = $targetPackage->website;
        $userPackage->module = $targetPackage->module;
        $userPackage->featured_module = $targetPackage->featured_module;
        $userPackage->storage = $targetPackage->storage;
        $userPackage->page = $targetPackage->page;
        $userPackage->save();

        return $subscription;
    }

    private function handlePaypalSwitchPackage($userPackage, $targetPackage, $modules)
    {
        $orderItem = OrderItem::find($userPackage->orderItem->id);

        $paypal = new Paypal();
        $provider = $paypal->getProvider();

        $subscriptionId = $orderItem->agreement_id;

        $result = $paypal->cancelSubscription($subscriptionId);

        //        if ($result) {
        $orderId = guid();
        $data = [];

        $data['items'] = [
            [
                'name' => $targetPackage->name,
                'price' => $targetPackage->prices->first()->price,
                'qty' => 1,
                'desc' => $targetPackage->name,
            ],
        ];
        $data['invoice_id'] = $orderId . "--" . $orderItem->order_item_id;
        $data['invoice_description'] = "Order #{$orderId} recurring product checkout";
        $data['return_url'] = route('user.website.getting.paypalSwitchPackage', ['id' => $userPackage->id, 'targetId' => $targetPackage->id]);
        $data['cancel_url'] = route('user.website.getting.resume', ['id' => $userPackage->id]) . '#/change_package';
        $data['notify_url'] = route("ipn.paypal");

        $data['no_shipping'] = 1;
        $data['total'] = $targetPackage->prices->first()->price;

        Session::put("paypalOrder" . $data['invoice_id'], $data);

        $response = $provider->setExpressCheckout($data, true);

        return $response['paypal_link'];
        //        }

        throw new \Exception('Error occurred while unsubscribing current plan');
    }

    public function paypalSwitchPackage($id, $targetId, Request $request)
    {
        try {
            $userPackage = UserPackage::with('orderItem', 'orderItem.order')->findorfail($id);
            $targetPackage = Package::findorfail($targetId);
            $orderItem = OrderItem::find($userPackage->orderItem->id);

            $token = $request->token;
            $payerId = $request->PayerID;
            if (empty($token)) {
                return redirect()->route('user.website.getting.resume', ['id' => $id])->with("error", 'Session is expired');
            }

            $paypal = new Paypal();
            $provider = $paypal->getProvider();
            $checkoutDetail = $provider->getExpressCheckoutDetails($token);

            $authuser = Auth::user();

            $invoice_id = $checkoutDetail['INVNUM'];
            $data = Session::get("paypalOrder" . $invoice_id);
            if (empty($data)) {
                return redirect()->route('user.website.getting.resume', ['id' => $id])->with("error", 'Session is expired');
            }

            $price = $targetPackage->prices->first();

            if ($price->period_unit == 'day') {
                $startdate = Carbon::now()->addDays($price->period)->toAtomString();
            } elseif ($price->period_unit == 'week') {
                $startdate = Carbon::now()->addWeeks($price->period)->toAtomString();
            } elseif ($price->period_unit == 'year') {
                $startdate = Carbon::now()->addYears($price->period)->toAtomString();
            } else {
                $startdate = Carbon::now()->addMonths($price->period)->toAtomString();
            }

            $detail = [
                'PROFILESTARTDATE' => $startdate,
                'DESC' => $targetPackage->name,
                'BILLINGPERIOD' => ucfirst($price->period_unit), // Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
                'BILLINGFREQUENCY' => $price->period, //
                'AMT' => $price->price, // Billing amount for each billing cycle
                'CURRENCYCODE' => 'USD', // Currency code
            ];

            $resp1 = $provider->doExpressCheckoutPayment($data, $token, $payerId);

            $resp2 = $provider->createRecurringPaymentsProfile($detail, $token);

            if ($resp1['ACK'] == 'Success') {
                $orderItem->agreement_id = $resp2['PROFILEID'] ?? 0;
                $orderItem->product_id = $targetPackage->id;
                $orderItem->sub_total = $targetPackage->prices->first()->price;
                $orderItem->product_detail = json_encode($targetPackage->toArray());
                $orderItem->price = json_encode($targetPackage->prices->first()->toArray());
                $orderItem->due_date = $startdate;
                $orderItem->save();

                $userPackage->item = json_encode($targetPackage->toArray());
                $userPackage->modules = json_encode([]);
                $userPackage->price = json_encode($targetPackage->prices->first()->toArray());
                $userPackage->website = $targetPackage->website;
                $userPackage->module = $targetPackage->module;
                $userPackage->featured_module = $targetPackage->featured_module;
                $userPackage->storage = $targetPackage->storage;
                $userPackage->page = $targetPackage->page;
                $userPackage->save();
            }

            return redirect()->to(route('user.website.getting.resume', ['id' => $id]) . '#/module')->with("success", 'Package switched successfully');
        } catch (\Exception $e) {
            return redirect()->route('user.website.getting.resume', ['id' => $id])->with("error", $e->getMessage());
        }
    }

    public function finish($id)
    {
        $userPackage = UserPackage::with('orderItem', 'orderItem.order', 'websites')->findorfail($id);
        $website = $userPackage->websites->first();

        return view('user.website.finish', compact('userPackage', 'website'));
    }
}
