<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class WebsiteMailAccount extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'mail_accounts';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function storeItem($request, $domain)
    {
        $item = $this;
        $item->web_id = 0;
        $item->domain_id = $domain->id;
        $item->username = $request->username;
        $item->email = $request->username . "@" . $domain->domain;
        $item->name = $request->name;
        $item->quota = $request->quota;
        $item->force_password_update = $request->force_password_update? 1:0;
        $item->sogo_access = 1;
        $item->status = $request->status? 'active':'inactive';
        $item->save();

        return $item;
    }
    public function updateItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->quota = $request->quota;
        $item->force_password_update = $request->force_password_update? 1:0;
        $item->sogo_access = 1;
        $item->status = $request->status? 'active':'inactive';
        $item->save();

        return $item;
    }
    public function domain()
    {
        return $this->belongsTo(WebsiteMailDomain::class, 'domain_id')->withDefault();
    }

    public function getDatatable($status, $domain_id)
    {
        if ($domain_id == 'all') {
            $emails = self::with('domain');
        } else {
            $emails = self::with('domain')->where("domain_id", $domain_id);
        }

        return Datatables::of($emails)->addColumn('domain_name', function ($row) {
            return "<a href='".route('admin.mail.account.index', $row->domain->id)."'>{$row->domain->domain}</a>";
        })
            ->editColumn('status', function ($row) {
                if ($row->status == 'active') {
                    $result = "<span class='c-badge c-badge-success'>Active</span>";
                } elseif ($row->status == 'inactive') {
                    $result = "<span class='c-badge c-badge-info'>InActive</span>";
                } else {
                    $result = "<span class='c-badge c-badge-primary'>".$row->status."</span>";
                }

                return $result;
            })->addColumn('action', function ($row) use ($domain_id) {
                if ($domain_id == 'all') {
                    return '
                        <a href="'.route('admin.mail.account.edit', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                        </a>';
                } else {
                    return '
                        <a href="'.route('admin.mail.account.edit', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon deleteBtn"  data-id="'.$row->id.'">
                            <span>
                                <i class="la la-remove"></i>
                                <span>Delete</span>
                            </span>
                        </a>';
                }
            })->rawColumns([ 'domain_name', 'status','action'])->make(true);
    }
}
