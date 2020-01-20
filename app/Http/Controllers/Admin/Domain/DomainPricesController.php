<?php

namespace App\Http\Controllers\Admin\Domain;

use App\Http\Controllers\Admin\AdminController;
use App\Jobs\DomainPriceGet;
use App\Models\DomainPrice;
use Illuminate\Http\Request;
use Validator;

class DomainPricesController extends AdminController
{
    public function __construct(DomainPrice $model)
    {
        $this->model = $model;
    }

    public function get()
    {
        DomainPriceGet::dispatch();

        return back()->with('success', 'Success! It is running in the background.');
    }

    public function switch(Request $request)
    {
        $validation = Validator::make($request->all(), $this->model->switchRule());
        if ($validation->fails()) {
            return response()->json(['error' => true, 'message' => $validation->errors()]);
        }

        $this->model->switchStatus($request);
        $response = [
            'message' => 'Successfully updated!',
        ];

        if ($request->ajax()) {
            return response()->json($response);
        }

        return redirect()->back()->with('message', $response['message']);
    }
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), $this->model->updateRule());
        if ($validation->fails()) {
            return response()->json(['error' => true, 'message' => $validation->errors()]);
        }
        $this->model->where('id', $id)->update($request->all());

        $response = [
            'message' => 'Successfully updated.',
        ];

        if ($request->ajax()) {
            return response()->json($response);
        }

        return redirect()->back()->with('message', $response['message']);
    }
}
