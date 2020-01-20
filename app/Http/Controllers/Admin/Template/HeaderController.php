<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Builder\TemplateHeader;
use Illuminate\Http\Request;
use Validator;

class HeaderController extends AdminController
{
    public function __construct(TemplateHeader $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $headers = $this->model->withCount('templates')->get();
            $activeHeaders = $headers->where('status', 1);
            $inactiveHeaders = $headers->where('status', 0);

            $all = view('components.admin.templateHeader', [
                'headers' => $headers,
                'selector' => 'datatable-all',
            ])->render();

            $active = view('components.admin.templateHeader', [
                'headers' => $activeHeaders,
                'selector' => 'datatable-active',
            ])->render();

            $inactive = view('components.admin.templateHeader', [
                'headers' => $inactiveHeaders,
                'selector' => 'datatable-inactive',
            ])->render();

            $count['all'] = $headers->count();
            $count['active'] = $activeHeaders->count();
            $count['inactive'] = $inactiveHeaders->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'templates.header');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $status = $request->status? 1:0;
            $request->merge(['status' => $status]);
            $data = $request->only('name', 'description', 'status', 'content', 'css', 'script');
            if ($request->header_id == null) {
                $data = $this->model->create($data);
            } else {
                $data = $this->model->find($request->header_id)->update($data);
            }

            return response()->json(['status' => 1, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function edit($id)
    {
        try {
            $header = $this->model->findorfail($id);

            return response()->json(['status' => 1, 'header' => $header]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
