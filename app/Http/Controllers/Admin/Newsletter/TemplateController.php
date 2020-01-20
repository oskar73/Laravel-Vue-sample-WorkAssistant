<?php

namespace App\Http\Controllers\Admin\Newsletter;

use App\Http\Controllers\Admin\AdminController;
use App\Models\NewsletterTemplate;
use Illuminate\Http\Request;

class TemplateController extends AdminController
{
    public function __construct(NewsletterTemplate $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->all();

            $view = view('components.admin.newsletterTemplate', [
                'items' => $items,
            ])->render();

            return response()->json([
                'status' => 1,
                'view' => $view,
            ]);
        }

        return view(self::$viewDir . 'newsletter.template');
    }

    public function create()
    {
        return view('admin.newsletter.templateEdit');
    }

    public function store(Request $request)
    {
        $newTemplate = $this->model->create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 1,
            'redirect' => route('admin.newsletter.template.edit', $newTemplate->slug),
        ]);
    }

    public function edit($slug)
    {
        $template = $this->model->where('slug', $slug)->firstorfail();

        return view(self::$viewDir . 'newsletter.templateEdit', compact('template'));
    }

    public function update(Request $request, $slug)
    {
        $template = $this->model->where('slug', $slug)->firstorfail();

        $template->html = $request->html;
        $template->modelData = $request->model;
        $template->save();

        return response()->json([
            'status' => 1,
        ]);
    }

    public function rename(Request $request, $slug)
    {
        info('here');
        $template = $this->model->where('slug', $slug)->firstorfail();

        $template->name = $request->name;
        $template->save();

        return response()->json([
            'status' => 1,
        ]);
    }

    public function preview($slug)
    {
        $template = $this->model->where('slug', $slug)->firstorfail();

        if (!$template->html) {
            return back()->with('error', 'Template is empty');
        }

        return response($template->html)->header('Content-Type', 'text/html');
    }

    public function delete($slug)
    {
        $template = $this->model->where('slug', $slug)->firstorfail();
        info($template);
        $template->delete();

        return response()->json([
            'status' => 1,
        ]);
    }
}
