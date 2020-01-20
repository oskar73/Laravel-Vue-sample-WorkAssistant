<?php

namespace App\Http\Controllers\Admin\TeamManage;

use App\Http\Controllers\Admin\AdminController;
use App\Models\TeamProperty;
use Illuminate\Http\Request;
use Validator;

class PropertyController extends AdminController
{
    public function __construct(TeamProperty $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $properties = $this->model->with('media')
                ->get();

            $activeProperties = $properties->where('status', 1);
            $inactiveProperties = $properties->where('status', 0);

            $all = view('components.admin.teamProperty', [
                'properties' => $properties,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.teamProperty', [
                'properties' => $activeProperties,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.teamProperty', [
                'properties' => $inactiveProperties,
                'selector' => "datatable-inactive",
            ])->render();

            $count['all'] = $properties->count();
            $count['active'] = $activeProperties->count();
            $count['inactive'] = $inactiveProperties->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'teamManage.property');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $status = $request->status? 1:0;
            $request->merge(['status' => $status]);
            $data = $request->only('name', 'description', 'status');
            if ($request->item_id == null) {
                $property = $this->model->create($data);
                // $property->addMediaFromBase64($request->thumbnail)
                //     ->usingFileName(guid() . ".jpg")
                //     ->toMediaCollection('image');

                $property->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            } else {
                $property = $this->model->find($request->item_id);
                $property->update($data);

                if ($request->thumbnail) {
                    // $property->clearMediaCollection('image');
                    // $property->addMediaFromBase64($request->thumbnail)
                    //     ->usingFileName(guid() . ".jpg")
                    //     ->toMediaCollection('image');

                    $property->clearMediaCollection("image")
                        ->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                        ->usingFileName(guid() . ".jpg")
                        ->toMediaCollection('image');
                }
            }

            return response()->json(['status' => 1, 'data' => $property]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
