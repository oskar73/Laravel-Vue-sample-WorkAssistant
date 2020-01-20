<?php

namespace App\Http\Controllers\User\Template;

use App\Http\Controllers\User\UserController;
use App\Models\Builder\Template;
use App\Models\Builder\TemplateCategory;
use App\Models\Builder\TemplateFooter;
use App\Models\Builder\TemplateHeader;
use App\Models\Builder\TemplatePage;
use App\Models\Builder\TemplatePageSection;
use App\Models\Country;
use App\Models\Theme;
use App\Models\ThemeCategory;
use App\Models\Website;
use App\Models\Website\Page as WebsitePage;
use App\Models\Website\PageSection;
use App\Repositories\PaletteRepository;
use App\Repositories\SectionRepository;
use App\Repositories\TemplateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends UserController
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
            $templates = $this->model->where('user_id', auth()->user()->id)->with('category.category', 'media', 'pages')->get();
            foreach ($templates as $template) {
                $themes = [];
                $colors = [];
                if (isset($template->data['theme']) && $template->data['theme'][$template->data['colorMode']]) {
                    $theme = $template->data['theme'][$template->data['colorMode']];
                    $color = $theme['name'] ?? '' . $theme['backgroundColor'] . $theme['primaryColor'] . $theme['buttonColor'] . $theme['socialIconColor'] . $theme['headingColor'] . $theme['boxColor'] . $theme['secondaryColor'];
                    array_push($colors, $color);
                    array_push($themes, $theme);
                }
                foreach ($template->pages as $page) {
                    if (isset($page->data['theme']) && $page->data['theme'][$template->data['colorMode']]) {
                        $theme = $page->data['theme'][$template->data['colorMode']];
                        $color = $theme['name'] ?? '' . $theme['backgroundColor'] . $theme['primaryColor'] . $theme['buttonColor'] . $theme['socialIconColor'] . $theme['headingColor'] . $theme['boxColor'] . $theme['secondaryColor'];
                        if (!in_array($color, $colors)) {
                            array_push($colors, $color);
                            array_push($themes, $theme);
                        }
                    }
                    foreach ($page->sections as $section) {
                        if (isset($section->data['theme'])) {
                            $theme = $section->data['theme'][$template->data['colorMode']];
                            $color = $theme['backgroundColor'] . $theme['primaryColor'] . $theme['buttonColor'] . $theme['socialIconColor'] . $theme['headingColor'] . $theme['boxColor'] . $theme['secondaryColor'];
                            if (!in_array($color, $colors)) {
                                array_push($colors, $color);
                                array_push($themes, $theme);
                            }
                        }
                    }
                }
                $template->themes = $themes;
            }
            $activeTemplates = $templates->where('status', 1);
            $inactiveTemplates = $templates->where('status', 0);

            $all = view('components.user.templateItem', [
                'templates' => $templates,
                'selector' => 'datatable-all',
            ])->render();

            $active = view('components.user.templateItem', [
                'templates' => $activeTemplates,
                'selector' => 'datatable-active',
            ])->render();

            $inactive = view('components.user.templateItem', [
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
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();
        $headers = TemplateHeader::select('id', 'name')
            ->status(1)->get();

        $footers = TemplateFooter::select('id', 'name')
            ->status(1)->get();

        return view('user.templates.index', compact('categories', 'headers', 'footers'));
    }

    public function preview(Request $request, $slug, $url = null)
    {
        $template = $this->model->where('slug', $slug)->with('pages')->first();
        $basePath = 'account/template/item/preview/' . $slug;

        return view('preview.website', compact('template', 'basePath'));
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule(), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $request->merge(['status' => 1]);
            $request->merge(['featured' => 0]);

            $data = $request->only('category_id', 'header_id', 'footer_id', 'name', 'description', 'status', 'featured', 'css', 'script', 'data', 'version');

            $defaultData = config('builder.default');
            $defaultData['name'] = $this->model->getUniqueValue('name', $data['name'], ['user_id' => $request->user()->id]);
            $data['data'] = $defaultData;

            $data['user_id'] = auth()->user()->id;

            $template = $this->model->create($data);

            $pages = $request->pages;
            if ($pages && is_array($pages)) {
                foreach ($pages as $page) {
                    $templatePage = new TemplatePage();
                    $templatePage->fill($page);
                    $templatePage->template_id = $template->id;
                    $templatePage->save();

                    foreach ($page['sections'] as $section) {
                        $newSection = new TemplatePageSection();
                        $newSection->fill($section);
                        $newSection->page_id = $templatePage->id;
                        $newSection->save();
                    }
                }
            }

            return $this->jsonSuccess($data);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function edit($id)
    {
        $template = $this->model->findorfail($id);

        $categories = $this->templateRepository->getCategories();

        return view("user.templates.editItem", compact('template', 'categories'));
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

    public function update(Request $request, Website $website): JsonResponse
    {
        DB::connection()->transaction(function () use ($request, $website) {
            $data = $request->only('category_id', 'name', 'description', 'status', 'featured', 'new', 'data');
            $website->update($data);

            // update preview image with request
            if ($request->file('image')) {
                $website->clearMediaCollection('preview')
                    ->addMediaFromRequest('image')
                    ->usingFileName(guid() . "." . $request->image->getClientOriginalExtension())
                    ->toMediaCollection('preview');
            }

            // update template pages
            $pages = $request->pages;
            if ($pages && is_array($pages)) {
                foreach ($pages as $page) {
                    $websitePage = Website\Page::find($page['id']);
                    $websitePage->update($page);

                    // delete old sections
                    $sectionIds = $websitePage->sections->map(function ($section) {
                        return $section->id;
                    });
                    PageSection::destroy($sectionIds);

                    $newSections = collect($page['sections'])->map(function ($section) use ($websitePage) {
                        return [
                            'page_id' => $websitePage->id,
                            'name' => $section['name'],
                            'category_id' => $section['category_id'],
                            'data' => json_encode($section['data']),
                        ];
                    })->all();

                    PageSection::insert($newSections);
                }
            }
        });

        return $this->jsonSuccess();
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

    public function getTemplateData($id): JsonResponse
    {
        try {
            $template = Website::where('id', $id)->with(
                ['pages' => function ($query) {
                    return $query->status(0)->orderBy('order');
                }]
            )->first();

            $allCategories = $this->sectionRepository->getSectionCategories();
            $systemPalettes = $this->paletteRepository->getAdminPalettes();
            $userPalettes = $this->paletteRepository->getUserPalettes();

            $templateCategories = TemplateCategory::with([
                'templates' => function ($q) {
                    $q->where('status', 1)->latest();
                },
            ])->where('status', 1)->latest()->get();

            $themeCategories = ThemeCategory::status(1)
                ->latest()
                ->get(['id', 'name', 'user_id']);

            $themes = Theme::status(1)->get();

            $modules = $template->getModules();

            return $this->jsonSuccess([
                'template' => $template,
                'templateCategories' => $templateCategories,
                'allCategories' => $allCategories,
                'systemPalettes' => $systemPalettes,
                'userPalettes' => $userPalettes,
                'themeCategories' => $themeCategories,
                'themes' => $themes,
                'countries' => Country::select(['iso', 'nicename'])->get(),
                'modules' => $modules,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function editPages($id)
    {
        $template = $this->templateRepository->getOne($id);

        return view("user.templates.editPage", compact("template"));
    }

    public function publishContent(string $id)
    {
        if (request()->wantsJson()) {
            try {
                $website = Website::where('id', $id)->first();

                if ($website === null) {
                    throw new \Exception("Website does not exist");
                }

                DB::beginTransaction();
                // delete pages and sections currently published
                WebsitePage::where('web_id', $website->id)->whereNull('type')->where('status', 1)->delete();

                // copy pages and sections of preview to published
                $previewPages = WebsitePage::where('web_id', $website->id)->where('status', 0)->with('sections')->get();

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

                // update website status
                $website->update(['status' => 'active', 'status_by_owner' => 'active']);

                DB::commit();

                return $this->jsonSuccess([
                    'status' => 1,
                ]);
            } catch (\Exception $exception) {
                DB::rollBack();

                return $this->jsonExceptionError($exception);
            }
        }

        return back()->with("error", "Sorry, website package is expired or website is banned");
    }

    public function updateWebsiteTheme(Request $request, $id)
    {
        try {
            $website = Website::find($id);
            $data = $website->data;
            $data['theme'] = $request->theme;
            $website->data = $data;
            $website->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
