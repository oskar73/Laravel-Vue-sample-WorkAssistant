<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Admin\AdminController;
use App\Models\EmailCategory;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Validator;

class TemplateController extends AdminController
{
    public function __construct(EmailTemplate $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
    }
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir . "email.template");
    }
    public function create()
    {
        $categories = EmailCategory::where("status", 1)
            ->orderBy("order")
            ->get();

        return view(self::$viewDir . "email.templateCreate", compact("categories"));
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
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            if ($request->template_id) {
                $item = $this->model->findorfail($request->template_id)
                    ->storeItem($request);
                $route = route('admin.email.template.edit', $item->id);
            } else {
                $item = $this->model->storeItem($request);
                $route = route('admin.email.template.edit', $item->id) . "#/body";
            }

            return response()->json(['status' => 1, 'data' => $route]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function edit($id)
    {
        $template = $this->model->findorfail($id);
        $categories = EmailCategory::where("status", 1)
            ->orderBy("order")
            ->get();

        return view(self::$viewDir . "email.templateEdit", compact("categories", "template"));
    }
    public function updateBody(Request $request, $id)
    {
        try {
            $template = $this->model->findorfail($id);
            $item = $template->updateBody($request);

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
