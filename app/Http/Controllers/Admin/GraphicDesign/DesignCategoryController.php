<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicCategory;
use Illuminate\Http\Request;

class DesignCategoryController extends Controller
{
    public function index(Request $request)
    {
        $slug = $request->get('slug');
        if ($slug) {
            $graphic = Graphic::findBySlug($slug);
        } else {
            $graphic = Graphic::first();
        }

        if (request()->wantsJson()) {
            try {
                $categories = GraphicCategory::where('graphic_id', $graphic->id)
                    ->latest()
                    ->select(['id', 'graphic_id', 'name', 'description', 'order', 'created_at'])
                    ->get();

                $all = view('components.admin.graphic-designs.designCategories', [
                    'categories' => $categories,
                    'selector' => "datatable-all",
                ])->render();

                $count['all'] = $categories->count();

                return response()->json([
                    'status' => 1,
                    'all' => $all,
                    'count' => $count,
                ]);
            } catch (\Exception $exception) {
                return $this->jsonExceptionError($exception);
            }
        };

        $graphics = Graphic::all();

        return view('admin.graphic-designs.designCategories', [
            'graphic' => $graphic,
            'graphics' => $graphics,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:45',
            'description' => 'max:6000',
            'thumbnail' => 'required',
            'graphic_id' => 'required',
            'category_id' => 'nullable|integer',
        ]);

        $category = new GraphicCategory();

        $category->storeItem($request);

        return $this->jsonSuccess();
    }

    public function destroy(string $id)
    {
        try {
            GraphicCategory::findOrFail($id)->delete();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
