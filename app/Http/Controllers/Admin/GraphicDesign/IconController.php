<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use Illuminate\Support\Facades\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class IconController extends Controller
{
    public function view()
    {
        $category = GraphicCategory::first();

        return redirect(route('admin.graphic-design.icons.index', $category->slug));
    }

    public function index(Request $request, GraphicCategory $category)
    {
        if (request()->wantsJson()) {
            try {
                $icons = $category->getMedia('icons');

                $all = view('components.admin.graphic-designs.icons', [
                    'icons' => $icons,
                    'selector' => "datatable-all",
                ])->render();

                $count['all'] = $icons->count();

                return response()->json([
                    'status' => 1,
                    'all' => $all,
                    'count' => $count,
                ]);
            } catch (\Exception $exception) {
                return $this->jsonExceptionError($exception);
            }
        };
        $categories = GraphicCategory::all();

        return view('admin.graphic-designs.icons', compact('category', 'categories'));
    }

    public function all()
    {
        try {
            $categories = GraphicCategory::all();
            $icons = [];
            foreach ($categories as $category) {
                $icons = array_merge(
                    $icons,
                    $category->getMedia('icons')->transform(function ($item) {
                        return ['id' => $item->id, 'url' => $item->original_url];
                    })->toArray()
                );
            }

            return response()->json([
                'status' => 1,
                'items' => $icons,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function store(Request $request, GraphicCategory $category)
    {
    }

    public function delete(Request $request, Media $icon)
    {
        return response()->json($icon->delete());
    }
}
