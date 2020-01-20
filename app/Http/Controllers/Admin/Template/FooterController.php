<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Builder\TemplateFooter;
use Illuminate\Http\Request;
use Validator;

class FooterController extends AdminController
{
    public function __construct(TemplateFooter $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $footers = $this->model->withCount('templates')->get();
            $activeFooters = $footers->where('status', 1);
            $inactiveFooters = $footers->where('status', 0);

            $all = view('components.admin.templateFooter', [
                'footers' => $footers,
                'selector' => 'datatable-all',
            ])->render();

            $active = view('components.admin.templateFooter', [
                'footers' => $activeFooters,
                'selector' => 'datatable-active',
            ])->render();

            $inactive = view('components.admin.templateFooter', [
                'footers' => $inactiveFooters,
                'selector' => 'datatable-inactive',
            ])->render();

            $count['all'] = $footers->count();
            $count['active'] = $activeFooters->count();
            $count['inactive'] = $inactiveFooters->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'templates.footer');
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
            if ($request->footer_id == null) {
                $data = $this->model->create($data);
            } else {
                $data = $this->model->find($request->footer_id)->update($data);
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
            $footer = $this->model->findorfail($id);

            return response()->json(['status' => 1, 'footer' => $footer]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
