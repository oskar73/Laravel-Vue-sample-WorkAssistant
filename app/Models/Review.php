<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Review extends Model
{
    protected $table = 'reviews';

    protected $guarded = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($obj) {
            if ($obj->status == "expired") {
                //                $obj->expiredNotification();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    public function ratable()
    {
        return $this->morphTo('ratable', 'model_type', 'model_id', 'id')->withDefault();
    }
    public function getProduct()
    {
        if ($this->ratable->slug) {
            if ($this->model == 'blogPackage') {
                return "<a href='".route('blog.package.detail', $this->ratable->slug)."'>".$this->ratable->name."</a>";
            } elseif ($this->model == 'portfolio') {
                return "<a href='".route('portfolio.detail', $this->ratable->slug)."'>".$this->ratable->title."</a>";
            } else {
                return "<a href='".route("{$this->model}.detail", $this->ratable->slug)."'>".$this->ratable->name."</a>";
            }
        } else {
            return "";
        }
    }
    public function getDatatable($status)
    {
        switch ($status) {
            case 'all':
                $reviews = $this::with('user', 'ratable')->latest();

                break;
            case 'active':
                $reviews = $this::with('user', 'ratable')->where('status', 1)->latest();

                break;
            case 'inactive':
                $reviews = $this::with('user', 'ratable')->where('status', 0)->latest();

                break;
        }

        return Datatables::of($reviews)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
        })->addColumn('product', function ($row) {
            return $row->getProduct();
        })->addColumn('user', function ($row) {
            $name = $row->user->name ?? $row->name . " (Unregistered)";
            $email = $row->user->email ?? $row->email;

            return $name ." <br> ". $email;
        })->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="c-badge c-badge-success hover-handle">Active</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>';
            } else {
                return '<span class="c-badge c-badge-danger hover-handle" >InActive</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>';
            }
        })->editColumn('model', function ($row) {
            return ucfirst($row->model);
        })->addColumn('action', function ($row) {
            return '
                    <a href="'.route('admin.review.show', $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>
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
        })->rawColumns(['checkbox', 'product', 'status','user', 'action'])->make(true);
    }
}
