<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Validator;

class TestimonialController extends AdminController
{
    public function __construct(Testimonial $model)
    {
        $this->model = $model;
        $this->sortModel = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->orderBy('order')->get();
            $selector = "datatable-all";
            $view = view('components.admin.testimonialTable', compact('items', 'selector'))->render();
            $count['all'] = $items->count();

            return response()->json([
                'status' => 1,
                'all' => $view,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'testimonial.index');
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
            $item = $this->model->storeItem($request);

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
