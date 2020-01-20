<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleCategory;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Module();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $categories = ModuleCategory::select('id', 'slug', 'name', 'parent_id')
            ->with('approvedSubCategories')
            ->isParent()
            ->status(1)
            ->orderBy('order')
            ->get();

        return view('frontend.module.index', compact('categories'));
    }

    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media', 'prices')
            ->status(1)
            ->firstorfail();

        return view('frontend.module.detail', compact('item'));
    }

    public function cartRule()
    {
        $rule['quantity'] = 'required|numeric|min:1';
        $rule['price'] = 'required';

        return $rule;
    }

    public function selectedModules(Request $request)
    {
        $modules = $request->hasModules ? $request->get('modules', []) : session("module_wishes", []);

        $items = $this->model->with('media', 'standardPrice', 'category')->whereIn('slug', $modules)->get();
        $modalView = view('components.user.selectedModules', compact('items'))->render();
        $sidebarView = view('components.user.selectedModulesTable', compact('items'))->render();
        $galleryView = count($modules) ? view('components.user.selectedModulesGallery', compact('items'))->render() : null;

        return $this->jsonSuccess([
            'modal' => $modalView,
            'sidebar' => $sidebarView,
            'gallery' => $galleryView,
        ]);
    }

    public function availableModules(Request $request)
    {
        $selectedModules = $request->hasModules ? $request->get('modules', []) : session("module_wishes", []);
        $items = $this->model->whereNotIn('slug', $selectedModules)->orderBy('slug', 'asc')->get();
        $view = view('components.user.availableModulesTable', compact('items'))->render();

        return $this->jsonSuccess($view);
    }

    public function addtoCart(Request $request, $id)
    {
        try {
            $item = $this->model->whereId($id)
                ->whereStatus(1)
                ->firstorfail();
            $slug = $item->slug;
            $module_wishes = session("module_wishes", []);
            if (!in_array($slug, $module_wishes)) {
                array_push($module_wishes, $slug);
                session()->put("module_wishes", $module_wishes);
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deltocart(Request $request)
    {
        try {
            $module = $request->module;
            $module_wishes = session("module_wishes", []);
            $module_wishes = array_diff($module_wishes, [$module]);
            session()->put("module_wishes", $module_wishes);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function moduleDetail(Request $request)
    {
        $id = $request->id;

        $item = $this->model->with('media', 'standardPrice', 'category')->findorfail($id);
        $view = view('components.user.viewModule', compact('item'))->render();

        return $this->jsonSuccess($view);
    }
}
