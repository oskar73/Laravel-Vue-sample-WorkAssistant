<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Builder\Template;
use App\Models\Builder\TemplatePage;
use App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TemplateController extends Controller
{
    public $model;
    private TemplateRepository $templateRepository;

    public function __construct(Template $template, TemplateRepository $templateRepository)
    {
        $this->model = $template;
        $this->templateRepository = $templateRepository;
    }

    public function index(Request $request)
    {
        $categories = $this->templateRepository->getCategories();

        return view('frontend.template.index', compact('categories'));
    }

    public function get(Request $request)
    {
        $result = $this->model->filterTemplate($request);

        return response()->json($result);
    }

    public function detail($slug)
    {
        $template = $this->model->where('slug', $slug)
            ->status(1)
            ->firstorfail(['header_id', 'id', 'footer_id', 'name', 'description', 'status', 'slug']);

        return view('layouts.templateApp', compact("template"));
    }

    public function preview($slug)
    {
        try {
            $template = Template::where('slug', $slug)
                ->status(1)
                ->firstorfail();

            $view = view("components.template.preview", compact("template"))->render();

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function view($slug)
    {
        $template = $this->model->where('slug', $slug)
            ->where('status', 1)
            ->with(['pages' => function ($query) {
                $query
//                    ->where('status', 1)
                    ->orderBy('order')->with('sections');
            }])->first();
        if (empty($template)) {
            abort(404, 'Template does not exists or inactive.');
        }
        $data = [
            'template' => $template,
            'slug' => $slug,
            'basePath' => '/',
        ];

        return view('preview.website', $data);
    }

    public function previewCore(Template $template, Request $request, $url = null)
    {
        if ($template->version == 1) {
            $header_id = Session::get('th' . $template->id, $template->header_id);
            $footer_id = Session::get('tf' . $template->id, $template->footer_id);

            if ($request->hid != null) {
                $header_id = $request->hid == 0 ? null : $request->hid;
                Session::put('th' . $template->id, $header_id);
            }

            if ($request->fid != null) {
                $footer_id = $request->fid == 0 ? null : $request->fid;
                Session::put('tf' . $template->id, $footer_id);
            }
            $template->header_id = $header_id;
            $template->footer_id = $footer_id;

            $template->load('header');
            $template->load('footer');

            $page = TemplatePage::where('url', $url)
                ->where('template_id', $template->id)
                ->where('status', 1)
                ->firstorfail();

            $sections = $page->sections;

            $data = optional($page->data);

            $preview = 0;
            if ($page->type === 'builder') {
                return view('preview.builder', compact('template', 'page', 'preview', 'header_id', 'footer_id', 'data', 'sections'));
            } else {
                return view('preview.box', compact('template', 'page', 'preview', 'header_id', 'footer_id', 'data', 'sections'));
            }
        }

        if ($request->wantsJson()) {
            return $this->jsonSuccess([
                'template' => $template,
            ]);
        }

        $page = TemplatePage::where('url', $url)
            ->where('template_id', $template->id)
            ->firstorfail();

        $seo = $page->data['seo'] ?? null;

        return view('preview.v2', compact('template', 'seo'));
    }

    public function templatePreview(Request $request, $id, $url = null)
    {
        $template = Template::where('id', $id)
            ->with(['pages' => function ($query) {
                $query->where('status', 0)->orderBy('order')->with('sections');
            }])->first();

        if (empty($template)) {
            abort(404, 'Template does not exists or inactive.');
        }

        $basePath = 'template-preview/' . $template->id;

        return view('preview.website', compact('template', 'basePath'));
    }

    public function start($slug)
    {
        $template = $this->model->where('slug', $slug)->where('status', 1)->firstorfail();
        $header_id = Session::get('th' . $template->id, $template->header_id);
        $footer_id = Session::get('th' . $template->id, $template->footer_id);

        $template['template'] = $template;
        $template['header_id'] = $header_id;
        $template['footer_id'] = $footer_id;

        Session::put("template", $template);

        return redirect()->route('package.index')->with("success", "Great, please pick one package plan");
    }
}
