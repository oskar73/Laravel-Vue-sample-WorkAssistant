<?php

namespace App\Http\Controllers\Admin\Directory;

use App\Http\Controllers\Admin\AdminController;
use App\Models\DirectoryCategory;
use App\Models\DirectoryTag;
use Illuminate\Http\Request;
use Validator;

class TagController extends AdminController
{
    public function __construct(DirectoryTag $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $tags = $this->model->with("categories:directory_categories.id,directory_categories.name")->get();

            $activeTags = $tags->where('status', 1);
            $inactiveTags = $tags->where('status', 0);

            $all = view('components.admin.directoryTag', [
                'tags' => $tags,
                'selector' => "datatable-all",
            ])->render();

            $active = view('components.admin.directoryTag', [
                'tags' => $activeTags,
                'selector' => "datatable-active",
            ])->render();

            $inactive = view('components.admin.directoryTag', [
                'tags' => $inactiveTags,
                'selector' => "datatable-inactive",
            ])->render();

            $count['all'] = $tags->count();
            $count['active'] = $activeTags->count();
            $count['inactive'] = $inactiveTags->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        $categories = DirectoryCategory::status(1)->get();

        return view(self::$viewDir.'directory.tag', compact('categories'));
    }
    public function store(Request $request)
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
            $tag = $this->model->storeItem($request);

            return $this->jsonSuccess($tag);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
