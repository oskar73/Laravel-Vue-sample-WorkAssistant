<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use Illuminate\Support\Facades\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageController extends Controller
{
    public function view()
    {
        $category = GraphicCategory::first();

        return redirect(route('admin.graphic-design.images.index', $category->slug));
    }

    public function index(Request $request, GraphicCategory $category)
    {
        if (request()->wantsJson()) {
            try {
                $images = $category->getMedia('images');

                $all = view('components.admin.graphic-designs.images', [
                    'images' => $images,
                    'selector' => "datatable-all",
                ])->render();

                $count['all'] = $images->count();

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

        return view('admin.graphic-designs.images', compact('category', 'categories'));
    }

    public function all()
    {
        try {
            $categories = GraphicCategory::all();
            $images = [];
            foreach ($categories as $category) {
                $images = array_merge(
                    $images,
                    $category->getMedia('images')->transform(function ($item) {
                        return ['id' => $item->id, 'url' => $item->original_url];
                    })->toArray()
                );
            }

            return response()->json([
                'status' => 1,
                'items' => $images,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function store(Request $request, GraphicCategory $category)
    {
    }

    public function delete(Request $request, Media $image)
    {
        return response()->json($image->delete());
    }
}
