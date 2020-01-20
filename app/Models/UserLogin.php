<?php

namespace App\Models;

use Yajra\DataTables\DataTables;

class UserLogin extends BaseModel
{
    protected $table = "user_logins";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function getDatatable($user)
    {
        $logins = $this::with('user');
        if ($user != 'all') {
            $logins = $logins->my($user);
        }

        return Datatables::of($logins)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
        })->addColumn('user', function ($row) {
            $result = "<a href=''>" . $row->user->name . "</a>";

            return $result;
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->addColumn('action', function ($row) {
            return '
                    <a href="javascript:void(0);" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3 switchOne" data-action="delete" title="Delete">
                            <i class="la la-remove"></i>
                    </a>';
        })->rawColumns(['checkbox','user', 'action'])
            ->make(true);
    }
}
