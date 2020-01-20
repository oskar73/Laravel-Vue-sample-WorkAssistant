<?php

namespace App\Models;

use Yajra\DataTables\DataTables;

class ProductCoupon extends BaseModel
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];
    public function storeRule($request)
    {
        $rule['name'] = 'required|string|max:45';
        if ($request->item_id == null) {
            $rule['code'] = 'required|string|unique:coupons,code';
        } else {
            $rule['code'] = 'required|string|unique:coupons,code,'.$request->item_id;
        }
        $rule['discount'] = 'required|numeric|between:0,100.00';
        $rule['expire_date'] = 'required|date_format:Y-m-d H:i';
        $rule['type'] = 'required|in:all,category,subCategory,product';
        if ($request->type != 'all') {
            $rule['product'] = 'required|integer';
            $rule['productType'] = 'required|string';
        }
        $rule['reusable'] = 'required|in:0,1';

        return $rule;
    }
    public function storeItem($request)
    {
        if ($request->item_id == null) {
            $coupon = $this;
        } else {
            $coupon = $this->findorfail($request->item_id);
        }
        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->discount = $request->discount;
        $coupon->type = $request->type;
        $coupon->expired_at = $request->expire_date;
        if ($request->type != 'all') {
            $coupon->model_id = $request->product;
            $coupon->model_type = $request->productType;
        } else {
            $coupon->model_id = 0;
        }
        $coupon->user_id = user()->id;
        $coupon->reusable = $request->reusable;
        $coupon->status = $request->has('status') ? 1:0;
        $coupon->save();

        return $coupon;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    public function model()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
    public function getProduct($type)
    {
        if ($type == 'package') {
            return $this->package;
        } elseif ($type == 'readymade') {
            return $this->readymade;
        } elseif ($type == 'lacarte') {
            return $this->lacarte;
        } elseif ($type == 'plugin') {
            return $this->plugin;
        } elseif ($type == 'service') {
            return $this->service;
        } elseif ($type == 'module') {
            return $this->module;
        }
    }
    public function getDatatable($status)
    {
        switch ($status) {
            case 'active':
                $reviews = $this::with('user', 'model')->where('status', 1)->latest();

                break;
            case 'inactive':
                $reviews = $this::with('user', 'model')->where('status', 0)->latest();

                break;
            case 'used':
                $reviews = $this::with('user', 'model')->where('status', -1)->latest();

                break;
        }

        return Datatables::of($reviews)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
        })->editColumn('type', function ($row) {
            if ($row->type == 'all') {
                return 'ALl Product Types';
            } else {
                return moduleName($row->type);
            }
        })->addColumn('product', function ($row) {
            if ($row->model_id == null || $row->model_id == 0) {
                $name = "All Products";
            } else {
                // $name = "All Products";
                // $name = $row->getProduct($row->type)->name;
                $name = $row->model->name;
            }

            return $name;
        })->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="c-badge c-badge-success hover-handle">Active</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>';
            } else {
                return '<span class="c-badge c-badge-danger hover-handle" >InActive</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>';
            }
        })->editColumn('code', function ($row) {
            return "<a href='javascript:void(0);' class='view_code text-dark' data-code='{$row->code}'><i class='fa fa-eye'></i></a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateTimeString();
        })->editColumn('reusable', function ($row) {
            if ($row->reusable == 1) {
                return '<span class="c-badge c-badge-success">Reusable</span>';
            } else {
                return '<span class="c-badge c-badge-info" >Onetime</span>';
            }
        })->addColumn('action', function ($row) {
            return '
                    <a href="javascript:void(0);" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-id="'.$row->id.'">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete">
                        <span>
                            <i class="la la-remove"></i>
                            <span>Delete</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox','status','code', 'action', 'reusable'])->make(true);
    }
}
