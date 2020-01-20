<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\User\UserController;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SizeController extends UserController
{
    public function __construct(ProductSize $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $sizes = $this->model->my()->get();

            return Datatables::of($sizes)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0);"
                            class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                            data-id="'.$row->id.'" data-name="'.$row->name.'" data-size_code="'.$row->size_code.'" data-slug="'.$row->slug.'"
                        >
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete">
                            <span>
                                <span>Delete</span>
                            </span>
                        </a>';
                })->rawColumns(['checkbox', 'action'])->make(true);
        }

        return view(self::$viewDir.'product.size');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->model->storeRule($request));

        if ($validation->fails()) {
            return response()->json([
                'status' => 0,
                'data' => $validation->errors(),
            ]);
        }

        $size = $this->model->storeUpdateItem($request);

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->updateRule($id));
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $item = $this->model->findorfail($id)->storeUpdateItem($request);

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
    public function switchListing(Request $request)
    {
        try {
            $action = $request->action;
            $sizes = $this->model->whereIn('id', $request->ids)->get();

            if ($action === 'approve') {
                $sizes->each->update(['status' => "approved", 'description' => $request->description, 'reason' => null]);
                $notify = 1;
                $data['description'] = $request->description;
            } elseif ($action === 'cancel') {
                $sizes->each->update(['status' => "canceled", 'reason' => $request->reason, 'description' => null]);
                $notify = 1;
                $data['reason'] = $request->reason;
            } elseif ($action === 'delete') {
                $sizes->each->delete();
            }

            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
