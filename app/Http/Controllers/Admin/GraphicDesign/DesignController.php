<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicCategory;
use App\Models\GraphicDesign\GraphicDesign;
use App\Models\GraphicDesign\GraphicDesignGroup;
use App\Models\GraphicDesign\UserDesign;
use App\Models\Tutorial;
use App\Repositories\FontRepository;
use App\Repositories\GraphicDesignRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DesignController extends Controller
{
    public GraphicDesign $model;
    public GraphicDesignRepository $repo;
    public GraphicDesign $sortModel;
    public FontRepository $fonts;

    public function __construct(GraphicDesign $model, GraphicDesignRepository $repo, FontRepository $fonts)
    {
        $this->model = $model;
        $this->repo = $repo;
        $this->fonts = $fonts;
        $this->sortModel = $this->model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view('admin.graphic-designs.design.index');
    }

    public function user(Request $request, $id)
    {
        if (request()->wantsJson()) {
            return (new UserDesign())->getDatatable(request("graphic_id", 'all'), $id);
        }

        return redirect()->route('admin.graphics.design.index');
    }

    public function create()
    {
        $graphics = Graphic::get(['id', 'title']);
        $categories = GraphicCategory::all();
        $tutorials = Tutorial::where("status", 1)
            ->orderBy("title")
            ->get(['id', 'title', 'public', 'status', 'order', 'slug', 'category_id']);

        $groups = Graphic::select(['id', 'title', 'slug'])
            ->with(['designs' => function ($query) {
                $query->select(['id', 'graphic_id', 'name']);
            }])
            ->get();

        return view('admin.graphic-designs.design.itemCreate', [
            'graphics' => $graphics,
            'groups' => $groups,
            'tutorials' => $tutorials,
            'categories' => $categories,
        ]);
    }

    public function storeRule()
    {
        $rule['svg_file'] = 'required|file|mimes:svg';
        $rule['preview_image'] = 'required|string';
        $rule['first_font'] = 'nullable|file';
        $rule['second_font'] = 'nullable|file';
        $rule['graphic_id'] = 'required|exists:graphics,id';
        $rule['category_ids'] = 'required|exists:graphic_categories,id';
        $rule['name'] = 'required|string|max:191';
        $rule['order'] = 'nullable|integer';
        $rule['global_order'] = 'nullable|integer';
        $rule['tutorial'] = 'nullable|integer|exists:tutorials,id,status,1';

        return $rule;
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->storeRule());
        if ($validation->fails()) {
            return response()->json(['status' => 0, 'errors' => $validation->errors()->all()]);
        }

        // Get logo content
        $designFile = $request->svg_file;
        $previewFile = $request->preview_image;

        // Get array fonts (if needed)
        $fonts = [
            $request->first_font,
            $request->second_font,
        ];

        $this->repo->create([
            'design' => $designFile,
            'preview' => $previewFile,
            'fonts' => $fonts,
            'request' => $request,
        ]);

        $hasNewFonts = false;
        // Add new fonts
        foreach ($fonts as $fontFile) {
            if ($fontFile) {
                $count = $this->fonts->create([
                    'font' => $fontFile,
                ]);
            }
        }

        return $this->jsonSuccess();
    }

    public function edit($id)
    {
        $design = $this->model->with('pairs')->findorfail($id);
        $graphics = Graphic::get(['id', 'title']);
        $categories = GraphicCategory::where('graphic_id', $design->graphic_id)->get();
        $tutorials = Tutorial::where("status", 1)
            ->orderBy("title")
            ->get(['id', 'title', 'public', 'status', 'order', 'slug', 'category_id']);

        $groups = Graphic::select(['id', 'title', 'slug'])
            ->where('id', '!=', $design->graphic_id)
            ->with(['designs' => function ($query) {
                $query->select(['id', 'graphic_id', 'name']);
            }])
            ->get();

        $pairs = $design->pairs->pluck('id')->toArray();
        $category_ids = $design->categories->pluck('id')->toArray();

        return view('admin.graphic-designs.design.itemEdit', [
            'graphics' => $graphics,
            'design' => $design,
            'categories' => $categories,
            'tutorials' => $tutorials,
            'groups' => $groups,
            'category_ids' => $category_ids,
            'pairs' => $pairs,
        ]);
    }

    public function updateRule($request)
    {
        $rule['preview_image'] = 'nullable|string';
        $rule['category_ids'] = 'required|exists:graphic_categories,id';
        $rule['name'] = 'required|string|max:191';
        $rule['order'] = 'nullable|integer';
        $rule['global_order'] = 'nullable|integer';
        $rule['tutorial'] = 'nullable|integer|exists:tutorials,id,status,1';

        return $rule;
    }

    public function update(Request $request, $id)
    {
        try {
            $design = $this->model->findorfail($id);
            $validation = Validator::make($request->all(), $this->updateRule($request));
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $design->updateItem($request);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function delete($id)
    {
        try {
            $this->model->findorfail($id)->deleteItem();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function switch(GraphicDesign $design)
    {
        try {
            $design->status = !$design->status;
            $design->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function download($hash)
    {
        $fileContents = Storage::disk($this->repo::DISK)->get("/designs/{$hash}.svg");

        $downloadName = "{$hash}.svg";

        return response()->streamDownload(function () use ($fileContents) {
            echo $fileContents;
        }, $downloadName, ['Content-Type' => 'application/svg']);
    }
}
