<?php

namespace App\Http\Controllers\Admin\Domain;

use App\Http\Controllers\Admin\AdminController;
use App\Jobs\DomainTldGet;
use App\Models\DomainTld;
use Illuminate\Http\Request;
use Validator;

class DomainTldsController extends AdminController
{
    public function __construct(DomainTld $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir.'domainTlds.index');
    }

    public function get()
    {
        DomainTldGet::dispatch();

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
            'message' => 'DomainTld updated successfully.',
        ];

        if ($request->ajax()) {
            return response()->json($response);
        }

        return redirect()->back()->with('message', $response['message']);
    }

    public function show($id)
    {
        $domainTld = $this->model->with('prices')->findorfail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $domainTld,
            ]);
        }

        return view(self::$viewDir.'domainTlds.show', compact('domainTld'));
    }

    public function edit($id)
    {
        $domainTld = $this->model->with('prices')->findorfail($id);

        return view(self::$viewDir.'domainTlds.edit', compact('domainTld'));
    }
}
