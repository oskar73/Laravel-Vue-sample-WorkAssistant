<?php

namespace App\Http\Controllers\Admin\ContentManagement;

use App\Http\Controllers\Admin\AdminController;
use App\Models\HomeSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeSliderController extends AdminController
{
    public function __construct(HomeSlider $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $sliders = HomeSlider::with(['media'])->get();

            return response()->json([
                'status' => 1,
                'table' => view('components.admin.home-slider', compact('sliders'))->render(),
            ]);
        }

        return view('admin.content-management.home-sliders');
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
            );

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $status = $request->status? 1:0;
            $request->merge(['status' => $status]);

            $data = $request->only('title', 'description', 'button', 'link', 'status');

            if ($request->slider_id === null) {
                $slider = $this->model->create($data);

                $slider->addMediaFromBase64(json_decode($request->image)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                $slider = $this->model->find($request->slider_id);
                $slider->update($data);

                if ($request->image) {
                    $slider->clearMediaCollection('image')
                        ->addMediaFromBase64(json_decode($request->image)->output->image)
                        ->usingFileName(guid() . ".jpg")
                        ->toMediaCollection('image');
                }
            }

            return response()->json(['status' => 1, 'data' => $slider]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
