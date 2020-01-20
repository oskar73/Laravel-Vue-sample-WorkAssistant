<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicMask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GraphicController extends Controller
{
    public $model;
    public $sortModel;

    public function __construct(Graphic $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            try {
                $graphics = $this->model->get();

                $all = view('components.admin.graphic-designs.category', [
                    'graphics' => $graphics,
                    'selector' => "datatable-all",
                ])->render();

                $count['all'] = $graphics->count();

                return response()->json([
                    'status' => 1,
                    'all' => $all,
                    'count' => $count,
                ]);
            } catch (\Exception $exception) {
                return $this->jsonExceptionError($exception);
            }
        };

        return view('admin.graphic-designs.index');
    }

    public function getRaw()
    {
        try {
            $categories = $this->model->get();
            foreach ($categories as $category) {
                $category->getMedia();
            }
            $count['all'] = $categories->count();

            return response()->json([
                'status' => 1,
                'categories' => $categories,
                'count' => $count,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function getSort(Request $request)
    {
        try {
            if ($request->cat_id == 0) {
                $items = $this->sortModel->select('id', 'name')
                    ->orderBy('order')
                    ->get();

                $view = '';
                foreach ($items as $item) {
                    $view .= '<li data-id="' . $item->id . '">' . $item->name . '</li>';
                }
            } else {
                $category = $this->model->findorfail($request->cat_id);
                $designs = $category->designs->sortBy("pivot.order");

                $view = '';
                foreach ($designs as $design) {
                    $view .= '<li data-id="' . $design->id . '"><img src="' . $design->preview . '" style="height:30px;margin-right:20px;"/>' . $design->name . '</li>';
                }
            }
            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateSort(Request $request)
    {
        try {
            $sorts = $request->get('sorts');
            if ($request->cat_id == 0) {
                foreach ($sorts as $key => $sort) {
                    $item = $this->model->find($sort);
                    $item->order = $key + 1;
                    $item->save();
                }
            } else {
                foreach ($sorts as $key => $sort) {
                    \DB::table('logo_category_types')->where("category_id", $request->cat_id)
                        ->where("logotype_id", $sort)
                        ->update(['order' => $key + 1]);
                }
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:45',
            'height' => 'required|integer',
            'width' => 'required|integer',
            'graphic_id' => 'nullable|integer',
        ]);

        $this->model->storeItem($request);

        return $this->jsonSuccess();
    }

    public function delete($id)
    {
        try {
            $this->model->findorfail($id)->delete();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function viewMasks(Request $request)
    {
        $slug = $request->get('slug');
        $graphics = Graphic::select(['id', 'slug', 'title'])->get();
        if ($slug) {
            $graphic = Graphic::findBySlug($slug);
        } else {
            $graphic = $graphics[0];
        }
        $masks = $graphic->getMedia('masks')->toArray();

        return view('admin.graphic-designs.masks', [
            'graphics' => $graphics,
            'graphic' => $graphic,
            'masks' => $masks,
        ]);
    }

    public function addMask(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'file' => 'required',
        ]);

        $category = Graphic::findBySlug($request->slug);

        $content = file_get_contents($request->file->getRealPath());
        $unableToDetermineSize = '';
        $sizeFits = true;
        if(Str::containsAll($content, ['height="', 'width="'])){
            $height = intval((explode('px"', explode('height="', $content)[1])[0]) ?? 0);
            $width = intval((explode('px"', explode('width="', $content)[1])[0]) ?? 0);
            $categoryHeight = $category->height;
            $categoryWidth = $category->width;
            $sizeFits = (($categoryHeight === $height) && ($categoryWidth === $width)) || (($categoryHeight/$categoryWidth) === ($height/$width));
        } else {
            $unableToDetermineSize = "Unable to determine size of svg file";
        }

        $mask = $category->addMediaFromRequest('file')
                    ->usingFileName(guid() . "." . $request->file->getClientOriginalExtension())
                    ->toMediaCollection('masks');

        if($content){
            $gm = new GraphicMask();
            $gm->storeItem($request, $category->id, $content, $mask->id);
        } else {
            Media::destroy([$mask->id]);
            return $this->jsonError([
                'message' => 'unable to retrieve content from selected svg!',
            ]);
        }

        return $this->jsonSuccess([
            'mask' => $mask,
            'message' => $sizeFits ? $unableToDetermineSize : 'Mask size doesn\'t fit this category!'
        ]);
    }

    public function viewImages(Request $request)
    {
        $slug = $request->get('slug');
        $categories = Graphic::select(['id', 'slug', 'title'])->get();
        if ($slug) {
            $category = Graphic::findBySlug($slug);
        } else {
            $category = $categories[0];
        }
        $images = $category->getMedia('images')->toArray();

        return view('admin.graphic-designs.images', [
            'category' => $category,
            'categories' => $categories,
            'images' => $images,
        ]);
    }

    public function addImage(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'file' => 'required',
        ]);

        $category = Graphic::findBySlug($request->slug);

        $image = $category->addMediaFromRequest('file')
            ->usingFileName(guid() . "." . $request->file->getClientOriginalExtension())
            ->toMediaCollection('images');

        return $this->jsonSuccess([
            'image' => $image,
        ]);
    }
    public function viewIcons(Request $request)
    {
        $slug = $request->get('slug');
        $categories = Graphic::select(['id', 'slug', 'title'])->get();
        if ($slug) {
            $category = Graphic::findBySlug($slug);
        } else {
            $category = $categories[0];
        }
        $icons = $category->getMedia('icons')->toArray();

        return view('admin.graphic-designs.icons', [
            'category' => $category,
            'categories' => $categories,
            'icons' => $icons,
        ]);
    }

    public function addIcon(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'file' => 'required',
        ]);

        $category = Graphic::findBySlug($request->slug);

        $icons = $category->addMediaFromRequest('file')
            ->usingFileName(guid() . "." . $request->file->getClientOriginalExtension())
            ->toMediaCollection('icons');

        return $this->jsonSuccess([
            'icon' => $icons,
        ]);
    }

    public function deleteMedia(Request $request)
    {
        $request->validate(['media_id' => 'required']);

        Media::destroy([$request->media_id]);
        GraphicMask::where('content_id', $request->media_id)->delete();

        return $this->jsonSuccess();
    }
}
