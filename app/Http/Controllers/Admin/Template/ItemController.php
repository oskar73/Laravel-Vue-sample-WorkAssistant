<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Builder\Template;
use App\Models\Builder\TemplateCategory;
use App\Models\Builder\TemplateFooter;
use App\Models\Builder\TemplateHeader;
use App\Models\Builder\TemplatePage;
use App\Models\Country;
use App\Models\Theme;
use App\Models\ThemeCategory;
use App\Repositories\PaletteRepository;
use App\Repositories\SectionRepository;
use App\Repositories\TemplateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends AdminController
{
    private TemplateRepository $templateRepository;
    private PaletteRepository $paletteRepository;
    private SectionRepository $sectionRepository;

    public function __construct(Template $model, PaletteRepository $paletteRepository, SectionRepository $sectionRepository, TemplateRepository $templateRepository)
    {
        $this->model = $model;
        $this->templateRepository = $templateRepository;
        $this->paletteRepository = $paletteRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $templates = $this->model->whereNull('user_id')->with(['category.category', 'media'])->latest()->get();
            $activeTemplates = $templates->whereNull('status', 1);
            $inactiveTemplates = $templates->whereNull('status', 0);

            $all = view('components.admin.templateItem', [
                'templates' => $templates,
                'selector' => 'datatable-all',
            ])->render();

            $active = view('components.admin.templateItem', [
                'templates' => $activeTemplates,
                'selector' => 'datatable-active',
            ])->render();

            $inactive = view('components.admin.templateItem', [
                'templates' => $inactiveTemplates,
                'selector' => 'datatable-inactive',
            ])->render();

            $count['all'] = $templates->count();
            $count['active'] = $activeTemplates->count();
            $count['inactive'] = $inactiveTemplates->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        $categories = TemplateCategory::where('parent_id', '==', 0)
            ->select(['id', 'name'])
            ->with('approvedSubCategories')
            ->status(1)
            ->get();
        $headers = TemplateHeader::select(['id', 'name'])
            ->status(1)->get();

        $footers = TemplateFooter::select(['id', 'name'])
            ->status(1)->get();

        return view('admin.templates.item', compact('categories', 'headers', 'footers'));
    }

    /**
     * Creates template
     * @ref website create route('website.getting.submit)
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule(), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $request->merge(['status' => $request->status ? 1 : 0]);
            $request->merge(['featured' => $request->featured ? 1 : 0]);

            $data = $request->only('category_id', 'header_id', 'footer_id', 'name', 'description', 'status', 'featured', 'css', 'script', 'data', 'version');

            $defaultData = config('builder.default');
            $defaultData['name'] = $request->name;
            $data['data'] = $defaultData;
            $template = $this->model->create($data);

            $home_page = new TemplatePage();
            $home_page->createTemplatePage($template->id, 0, 'Home');

            $new_page = new TemplatePage();
            $new_page->createTemplatePage($template->id, 0, 'New Page', true);


            return $this->jsonSuccess($data);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function edit($id)
    {
        $template = $this->model->findorfail($id);

        $categories = $this->templateRepository->getCategories();

        return view("admin.templates.editItem", compact('template', 'categories'));
    }

    public function preview(Request $request, $slug, $url = null)
    {
        $template = $this->model->where('slug', $slug)->with('pages')->first();
        $basePath = 'admin/template/item/preview/' . $slug;

        return view('preview.website', compact('template', 'basePath'));
    }

    public function updateTemplate(Request $request, $id)
    {
        try {
            $this->templateRepository->updateOne($request, $id);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function update(Request $request, Template $template): JsonResponse
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'data' => 'required',
        ]);

        $data = $request->only('category_id', 'name', 'description', 'status', 'featured', 'new', 'data');
        $template->update($data);

        // update template pages
        $pages = $request->pages;
        if ($pages && is_array($pages)) {
            foreach ($pages as $page) {
                $templatePage = TemplatePage::find($page['id']);
                $templatePage->savePage($page);
            }
        }

        return $this->jsonSuccess();
        //            // update preview image with base64 image
        //            if ($request->image && strpos($request->image, 'base64') > 0) {
        //                $template->clearMediaCollection('preview')
        //                    ->addMediaFromBase64($request->image)
        //                    ->usingFileName(guid() . ".png")
        //                    ->toMediaCollection('preview');
        //            }
        //
        //            // update preview image with request
        //            if ($request->file('image')) {
        //                $template->clearMediaCollection('preview')
        //                    ->addMediaFromRequest('image')
        //                    ->usingFileName(guid() . "." . $request->image->getClientOriginalExtension())
        //                    ->toMediaCollection('preview');
        //            }


    }

    // update theme only in template data
    public function updateTemplateTheme(Request $request, $id)
    {
        try {
            $template = Template::find($id);
            $data = $template->data;
            $data['theme'] = $request->theme;
            $template->data = $data;
            $template->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function editPages(Template $template)
    {
        $template->load(['pages' => function ($query) {
            $query->where('status', 0);
        }]);

        return view("admin.templates.editPage", [
            'template' => $template,
            'basePath' => '/admin/template/item/editPages/' . $template->id,
        ]);
    }

    public function getTemplateData($id): JsonResponse
    {
        try {
            $template = $this->model::where('id', $id)->with(
                ['pages' => function ($query) {
                    return $query->status(0)->orderBy('order');
                }]
            )->firstOrFail();


            $allCategories = $this->sectionRepository->getSectionCategories();
            $systemPalettes = $this->paletteRepository->getAdminPalettes();

            $themeCategories = ThemeCategory::whereNull('user_id')
                ->select(['id', 'user_id', 'name'])
                ->where('status', 1)
                ->latest()
                ->get(['id', 'name', 'user_id']);

            $themes = Theme::whereNull('user_id')
                ->where('status', 1)
                ->get();

            $templateCategories = TemplateCategory::withWhereHas('templates', function ($q) {
                $q->where('status', 1)->latest();
            })
                ->where('status', 1)
                ->latest()->get();

            return $this->jsonSuccess([
                'template' => $template,
                'templateCategories' => $templateCategories,
                'allCategories' => $allCategories,
                'systemPalettes' => $systemPalettes,
                'themeCategories' => $themeCategories,
                'themes' => $themes,
                'countries' => Country::select(['iso', 'nicename'])->get(),
                'modules' => [
                    'blog' => [
                        'categories' => null,
                        'posts' => [],
                        'page' => null,
                    ],
                ],
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function getTemplate($id)
    {
        $template = $this->model::where('id', $id)->with(
            ['pages' => function ($query) {
                return $query->status(0)->orderBy('order');
            }]
        )->firstOrFail();

        return $this->jsonSuccess([
            'template' => $template,
        ]);
    }

    public function getTemplatePreviewData($id): object
    {
        $template = $this->model->where('id', $id)->with(['pages' => function ($query) {
            $query->orderBy('order');
        }])->first();

        return $this->jsonSuccess([
            'template' => $template,
        ]);
    }

    public function uploadPreviewUrl(Request $request, $id): JsonResponse
    {
        try {
            $template = $this->model::find($id);
            if ($template == null) {
                throw new \Exception('Template does not exist');
            }

            // update preview image with request
            if ($request->file('image')) {
                $template->savePreviewUrl($request);
            }

            return $this->jsonSuccess('Successfully updated!');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    /**
     * @param $id
     */
    public function publishContent($id)
    {
        if (request()->wantsJson()) {
            try {
                $template = $this->model::where('id', $id)->first();

                if ($template === null) {
                    throw new \Exception("Template does not exist");
                }

                DB::beginTransaction();
                // delete pages and sections currently published
                TemplatePage::where('template_id', $template->id)->where('status', 1)->delete();

                // copy pages and sections of preview to published
                $previewPages = TemplatePage::where('template_id', $template->id)->where('status', 0)->with('sections')->get();

                foreach ($previewPages as $previewPage) {
                    $publishedPage = $previewPage->replicate();
                    $publishedPage->push();
                    $publishedPage->save();

                    foreach ($previewPage->sections as $section) {
                        $newSection = $section->replicate();
                        $newSection->page_id = $publishedPage->id;
                        $newSection->push();
                        $newSection->save();
                    }

                    $publishedPage->update(['status' => 1]);
                }

                // update template status
                $template->update(['status' => 1]);

                DB::commit();

                return $this->jsonSuccess([
                    'status' => 1,
                ]);
            } catch (\Exception $exception) {
                DB::rollBack();

                return $this->jsonExceptionError($exception);
            }
        }

        return back()->with("error", "Sorry, template package is expired or template is banned");
    }
}
