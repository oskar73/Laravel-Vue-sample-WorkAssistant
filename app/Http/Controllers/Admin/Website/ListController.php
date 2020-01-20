<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Builder\Template;
use App\Models\Builder\TemplateFooter;
use App\Models\Builder\TemplateHeader;
use App\Models\Domain;
use App\Models\DomainConnect;
use App\Models\DomainCustom;
use App\Models\Module;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteFooter;
use App\Models\WebsiteHeader;
use App\Models\WebsiteModule;
use Illuminate\Http\Request;
use Validator;

class ListController extends AdminController
{
    public function __construct(Website $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"), request()->get("user"));
        }

        return view(self::$viewDir.'websites.index');
    }
    public function create()
    {
        $templateModel = new Template();
        $templates = $templateModel->approvedAllTemplatesQuery()->status(1)
            ->select('id', 'name', 'slug', "header_id", "footer_id")
            ->with('media')
            ->get();

        $domains = Domain::where('user_id', user()->id)
            ->select('name', 'user_id', 'pointed', 'status', 'expired_at', 'created_at', 'id')
            ->whereNull('web_id')
            ->orderBy('status')
            ->get();

        $users = User::status('active')->select('id', 'first_name', 'last_name', 'username', 'email')->get();

        $headers = TemplateHeader::status(1)->get(['id', 'name']);
        $footers = TemplateFooter::status(1)->get(['id', 'name']);

        $modules = Module::status(1)->get(['id', 'name', 'slug', 'description', 'new', 'featured']);

        return view(self::$viewDir.'websites.create', compact('templates', 'domains', 'users', 'headers', 'footers', 'modules'));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $module_ids = $request->modules ?? [];

            $modules = Module::whereIn("id", $module_ids)->where("status", 1)->get();

            $module_counts = $modules->count();
            $featured_counts = $modules->where("featured", 1)->count();

            if ($request->module_limit != -1 && ($request->module_limit - $module_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max module limit count is '. $request->module_limit]]);
            }
            if ($request->featured_module_limit != -1 && ($request->featured_module_limit - $featured_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max featured module limit count is '.$request->featured_module_limit]]);
            }


            $template = Template::find($request->template);
            $c_header = TemplateHeader::status(1)->whereId($request->header)->first();
            $c_footer = TemplateFooter::status(1)->whereId($request->footer)->first();

            $website = $this->model->storeWebsite($request, $template);

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
                'mainCss' => $c_footer->mainCss ?? '',
                'sectionCss' => $c_footer->sectionCss ?? '',
                'script' => $c_footer->script ?? '',
            ]);

            $website->createModules($modules);

            if ($template) {
                $website->createPages($template->approvedPages);
            }

            $website->createAdmin($request, $request->owner);

            return response()->json([
                'status' => 1,
                'data' => $website,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function show(Request $request, $id)
    {
        $website = $this->model->with("activeModules")
            ->findorfail($id);
        if ($request->ajax()) {
            $view = view("components.account.userWebsiteDomain", compact('website'))->render();

            return response()->json([
                'status' => 1,
                'data' => $view,
            ]);
        }
        $modules = Module::where("status", 1)->get(['name', 'slug', 'featured', 'id']);

        return view(self::$viewDir . "websites.edit", compact('website', 'modules'));
    }
    public function edit(Request $request, $id)
    {
        $website = $this->model->with("activeModules")
        ->findorfail($id);
        if ($request->ajax()) {
            $view = view("components.account.userWebsiteDomain", compact('website'))->render();

            return response()->json([
                'status' => 1,
                'data' => $view,
            ]);
        }
        $modules = Module::where("status", 1)->get(['name', 'slug', 'featured', 'id']);

        return view(self::$viewDir . "websites.edit", compact('website', 'modules'));
    }
    public function updateModule(Request $request, $id)
    {
        try {
            $website = Website::whereId($id)
                ->firstorfail();

            $website->module_limit = $request->module_limit;
            $website->fmodule_limit = $request->featured_module_limit;
            $website->save();

            $module_ids = $request->modules ?? [];

            $modules = Module::whereIn("id", $module_ids)->where("status", 1)->get();

            $module_counts = $modules->count();
            $featured_counts = $modules->where("featured", 1)->count();

            if ($request->module_limit != -1 && ($request->module_limit - $module_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max module limit count is '. $request->module_limit]]);
            }
            if ($request->featured_module_limit != -1 && ($request->featured_module_limit - $featured_counts) < 0) {
                return response()->json(['status' => 0, 'data' => ['Your max featured module limit count is '.$request->featured_module_limit]]);
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

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function updateDomain(Request $request, $id)
    {
        try {
            $website = Website::whereId($id)
                ->firstorfail();

            $validation = Validator::make($request->all(), [
                'subdomain' => 'required|string|min:4|max:191|unique:domain_customs,subdomain',
            ]);

            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $domain = DomainCustom::findorfail($request->id);
            //            $domain = DomainCustom::where("web_id", $website->id)->firstorfail();

            $domain->subdomain = $request->subdomain;
            $domain->name = $request->subdomain . "." .optional(option("ssh"))['root_domain'];
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

            return response()->json([
                'status' => 1,
                'data' => $domain,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function setPrimary(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->setPrimaryRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $website = $this->model->findorfail($id);

            $domain_id = $request->id;
            $domain_type = $request->domain_type;
            if ($domain_type === 'hosted') {
                $domain = Domain::find($domain_id);
            } elseif ($domain_type === 'connected') {
                $domain = DomainConnect::find($domain_id);
            } elseif ($domain_type === 'subdomain') {
                $domain = DomainCustom::find($domain_id);
            }
            $web = $website->primaryDomain;
            $web->web_id = null;
            $web->save();

            $domain->web_id = $website->id;
            $domain->save();

            $website->domain = $domain->name;
            $website->domain_type = $request->domain_type;
            $website->save();


            return response()->json([
                'status' => 1,
                'data' => $website,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function basicUpdate(Request $request, $id)
    {
        try {
            $website = $this->model->findorfail($id);

            $validation = Validator::make($request->all(), [
                'name' => 'max:191',
                'status' => 'required|in:active,pending,canceled,expired',
                'status_by_owner' => 'required|in:active,pending,maintenance',
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            $website->name = $request->name;
            $website->status = $request->status;
            $website->status_by_owner = $request->status_by_owner;
            $website->save();

            return response()->json([
                'status' => 1,
                'data' => $website,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function profileUpdate(Request $request, $id)
    {
        try {
            $website = $this->model->findorfail($id);
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

            return response()->json([
                'status' => 1,
                'data' => $admin,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function loadCustom()
    {
        try {
            $customs = DomainConnect::where('user_id', user()->id)
                ->select(['name', 'user_id', 'pointed', 'data', 'created_at', 'id'])
                ->whereNull('web_id')
                ->orderBy('pointed')
                ->get();
            $view = view('components.domain.custom', compact('customs'))->render();

            return response()->json([
                'status' => 1,
                'data' => $view,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
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
}
