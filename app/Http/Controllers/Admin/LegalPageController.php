<?php

namespace App\Http\Controllers\Admin;

use App\Models\LegalPage;
use Illuminate\Http\Request;
use Validator;

class LegalPageController extends AdminController
{
    public function __construct(LegalPage $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->get();
            $view = view('components.admin.legalPageTable', compact('items'))->render();
            $count['all'] = $items->count();

            return response()->json([
                'status' => 1,
                'all' => $view,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'legal.index');
    }
    public function edit($slug)
    {
        $page = $this->model->whereSlug($slug)->firstorfail();

        return view(self::$viewDir.'legal.edit', compact("page"));
    }

    public function update(Request $request, $slug)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request)
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $item = $this->model->whereSlug($slug)
                ->firstorfail()
                ->updateItem($request);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
