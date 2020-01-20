<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\User\UserController;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UnitController extends UserController
{
    public function __construct(ProductUnit $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $units = $this->model->my()->get();

            return Datatables::of($units)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0);"
                            class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                            data-id="'.$row->id.'" data-name="'.$row->name.'"
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

        return view(self::$viewDir.'product.unit');
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

        $unit = $this->model->storeUpdateItem($request);

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
            $units = $this->model->whereIn('id', $request->ids)->get();

            if ($action === 'approve') {
                $units->each->update(['status' => "approved", 'description' => $request->description, 'reason' => null]);
                $notify = 1;
                $data['description'] = $request->description;
            } elseif ($action === 'cancel') {
                $units->each->update(['status' => "canceled", 'reason' => $request->reason, 'description' => null]);
                $notify = 1;
                $data['reason'] = $request->reason;
            } elseif ($action === 'delete') {
                $units->each->delete();
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
