<?php

namespace App\Http\Controllers\Admin\ContentManagement;

use App\Http\Controllers\Admin\AdminController;
use App\Models\HomeBox;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoxesController extends AdminController
{
    use ImageUploadTrait;

    public function __construct(HomeBox $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $boxes = HomeBox::with('media')->get();

            return response()->json([
                'status' => 1,
                'table' => view('components.admin.home-boxes', compact('boxes'))->render(),
            ]);
        }

        return view('admin.content-management.boxes');
    }

    public function middleBox(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsonError($validator->errors());
        }

        $imagePaths = $this->uploadSlimImageFromRequest($request->image, [
            "imageName" => "uploads/home-front-box-image.png",
            "thumbName" => "uploads/home-front-box-image-thumb.png",
        ]);

        option([
            'home.front.box.image' => $imagePaths['imagePath'],
            'home.front.box.image-thumb' => $imagePaths['thumbPath'],
            'home.front.box.link' => $request->link,
        ]);

        return $this->jsonSuccess();
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

            $status = $request->status ? 1 : 0;
            $request->merge(['status' => $status]);

            $data = $request->only('name', 'description', 'link', 'status');

            if ($request->box_id === null) {
                $box = $this->model->create($data);

                $box->addMediaFromBase64(json_decode($request->image)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                $box = $this->model->find($request->box_id);
                $box->update($data);

                if ($request->image) {
                    $box->clearMediaCollection('image')
                        ->addMediaFromBase64(json_decode($request->image)->output->image)
                        ->usingFileName(guid() . ".jpg")
                        ->toMediaCollection('image');
                }
            }

            return response()->json(['status' => 1, 'data' => $box]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
