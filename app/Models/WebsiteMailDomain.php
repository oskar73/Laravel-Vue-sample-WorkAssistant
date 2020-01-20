<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class WebsiteMailDomain extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'mail_domains';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function storeItem($request)
    {
        $item = $this;
        $item->web_id = 0;
        $item->domain_type = "connected";
        $item->domain = $request->domain;
        $item->description = $request->description;
        $item->max_aliases = $request->max_aliases;
        $item->max_mail_boxes = $request->max_mail_boxes;
        $item->default_mailbox_quota = $request->default_mailbox_quota;
        $item->max_quota_per_mailbox = $request->max_quota_per_mailbox;
        $item->total_quota = $request->domain_total_quota;
        $item->rate_limit = $request->rate_limit;
        $item->rate_limit_unit = $request->rate_limit_unit;
        $item->status = $request->status?1:0;
        $item->save();

        return $item;
    }

    public function updateItem($request)
    {
        $item = $this;
        $item->description = $request->description;
        $item->max_aliases = $request->max_aliases;
        $item->max_mail_boxes = $request->max_mail_boxes;
        $item->default_mailbox_quota = $request->default_mailbox_quota;
        $item->max_quota_per_mailbox = $request->max_quota_per_mailbox;
        $item->total_quota = $request->domain_total_quota;
        $item->status = $request->status?1:0;
        $item->save();

        return $item;
    }

    public function accounts()
    {
        return $this->hasMany(WebsiteMailAccount::class, 'domain_id');
    }
    public function canCreateAccount()
    {
        return ($this->status && ($this->max_mail_boxes - $this->accounts->count()));
    }
    public function totalQuota()
    {
        return $this->accounts->sum("quota");
    }

    public function getDatatable($status)
    {
        switch ($status) {
            case 'all':
                $domains = self::withCount("accounts");

                break;
            case 'active':
                $domains = self::withCount("accounts")->where('status', 'active');

                break;
            case 'inactive':
                $domains = self::withCount("accounts")->where('status', 'inactive');

                break;
        }

        return Datatables::of($domains)->editColumn("domain", function ($row) {
            return "<a href='".route('admin.mail.account.index', $row->id)."'>".$row->domain."</a>";
        })->editColumn("max_mail_boxes", function ($row) {
            $text = $row->accounts_count . " / " . $row->max_mail_boxes;

            return "<a href='".route('admin.mail.account.index', $row->id)."'>".$text."</a>";
        })
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    $result = "<span class='c-badge c-badge-success'>Active</span>";
                } elseif ($row->status == 0) {
                    $result = "<span class='c-badge c-badge-info'>InActive</span>";
                }

                return $result;
            })->addColumn('action', function ($row) {
                if ($row->accounts_count) {
                    return '
                    <a href="'.route('admin.mail.domain.edit', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>';
                } else {
                    return '
                    <a href="'.route('admin.mail.domain.edit', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
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
            })->rawColumns([ 'domain', 'max_mail_boxes', 'status','action'])->make(true);
    }
}
