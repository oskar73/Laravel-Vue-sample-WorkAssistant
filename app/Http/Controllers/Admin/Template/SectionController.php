<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Builder\Section;
use App\Models\Builder\SectionCategory;
use App\Models\Builder\TemplatePage;
use App\Models\Builder\TemplatePageSection;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SectionController extends AdminController
{
    public function __construct(SectionCategory $model, Request $request)
    {
        $id = $request->category_id ?? $request->get('category_id') ?? $request->route('id');
        $this->model = $model::find($id) ?? $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $categories = $this->model->withCount('sections')
                ->latest()
                ->get(['id', 'name', 'description', 'status']);
            $sections = Section::with("category", "media")->latest()->get();

            $category = view('components.admin.templateSectionCategory', [
                'categories' => $categories,
                'selector' => 'datatable-category',
            ])->render();

            $section = view('components.admin.templateSection', [
                'sections' => $sections,
                'selector' => 'datatable-section',
            ])->render();

            $count['category'] = $categories->count();
            $count['section'] = $sections->count();

            return response()->json([
                'status' => 1,
                'category' => $category,
                'section' => $section,
                'count' => $count,
            ]);
        }

        $modules = Module::getModules();
        $data = array('modules' => $modules);

        return view(self::$viewDir . 'templates.section')->with($data);
    }

    public function categoryStore(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $this->model->storeItem($request);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function itemStore(Request $request, $id)
    {
        try {
            $category = $this->model->findorfail($id);

            $section = new Section();

            $validation = Validator::make(
                $request->all(),
                $section->storeRule($request),
                $section::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $section->storeItem($request, $category);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function itemUpdate(Request $request, $id)
    {
        try {
            $section = Section::with('category')->findorfail($id);
            $validation = Validator::make(
                $request->all(),
                $section->updateRule($request),
                $section::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $section->updateItem($request);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function itemSwitch(Request $request)
    {
        try {
            $action = $request->action;

            $items = Section::whereIn('id', $request->ids)->get();

            if ($action === 'active') {
                $items->each->update(['status' => 1]);
            } elseif ($action === 'inactive') {
                $items->each->update(['status' => 0]);
            } elseif ($action === 'delete') {
                $items->each->delete();
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function create($id)
    {
        $category = $this->model->findorfail($id);

        return view(self::$viewDir . 'templates.sectionCreate', compact("category"));
    }
    public function edit($id)
    {
        $section = Section::findorfail($id);

        $categories = SectionCategory::where("status", 1)
            ->with('sections')
            ->get();

        return view(self::$viewDir . 'templates.sectionEdit', compact("section", "categories"));
    }

    public function add(Request $request)
    {
        try {
            $category = $this->model->findOrFail($request->category);
            $categorySection = Section::where('category_id', $category->id)->first();

            DB::transaction(function () use ($request, $category, $categorySection) {
                $category->limit_per_page = $category->limit_per_page + 1;
                $category->save();

                $templatePages = TemplatePage::get();
                foreach ($templatePages as $templatePage) {
                    if ($templatePage->id == $request->page) {
                        $sections = TemplatePageSection::where('page_id', $request->page)->get();
                        TemplatePageSection::where('page_id', $request->page)->delete();

                        $isCreated = false;
                        foreach ($sections as $key => $section) {
                            if ($key == $request->position) {
                                $data = $categorySection->data;
                                $data->setting->visible = true;
                                $newSection = new TemplatePageSection();
                                $newSection->page_id = $request->page;
                                $newSection->name = $categorySection->name;
                                $newSection->category_id = $category->id;
                                $newSection->data = $data;
                                $newSection->save();
                                $isCreated = true;
                            }
                            $newSection = new TemplatePageSection();
                            $newSection->page_id = $request->page;
                            $newSection->name = $section['name'];
                            $newSection->category_id = $section['category_id'];
                            $newSection->data = $section['data'];
                            $newSection->save();
                        }

                        if (!$isCreated) {
                            $data = $categorySection->data;
                            $data->setting->visible = true;
                            $newSection = new TemplatePageSection();
                            $newSection->page_id = $request->page;
                            $newSection->name = $categorySection->name;
                            $newSection->category_id = $category->id;
                            $newSection->data = $categorySection->data;
                            $newSection->save();
                            $isCreated = true;
                        }
                    } else {
                        $data = $categorySection->data;
                        $data->setting->visible = false;
                        TemplatePageSection::create([
                            'page_id'   =>  $templatePage->id,
                            'category_id'   =>  $category->id,
                            'name'  =>  $categorySection->name,
                            'data'  =>  $data
                        ]);
                    }
                }
            });

            return response()->json([
                'status' => 1,
                'section' => [
                    'name'  =>  $categorySection->name,
                    'data'  =>  $categorySection->data
                ]
            ]);
            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}