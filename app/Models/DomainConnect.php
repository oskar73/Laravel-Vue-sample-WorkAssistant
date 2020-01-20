<?php

namespace App\Models;

use Yajra\DataTables\DataTables;

class DomainConnect extends BaseModel
{
    protected $connection = 'mysql';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'domain_connects';

    public function website()
    {
        return $this->belongsTo(Website::class, 'web_id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function park($domain)
    {
        return $this->create([
            'user_id' => user()->id,
            'name' => $domain,
        ]);
    }

    public function getDatatable()
    {
        $domains = $this->with('user', 'website')->select('*');

        return DataTables::of($domains)->editColumn('created_at', function ($row) {
            return $row->created_at->toDateString();
        })->addColumn('user', function ($row) {
            return "<a href='".route('admin.userManage.detail', $row->user_id ?? 0)."'>{$row->user->name}</a>";
        })->addColumn('website', function ($row) {
            if ($row->web_id == null) {
                return '';
            } else {
                return "<a href='".route('admin.website.list.show', $row->web_id)."'>{$row->website->name}</a>";
            }
        })->addColumn('action', function ($row) {
            return "
                    <a href='javascript:void(0);' class='btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon disconnect' data-id='{$row->id}'>
                        <span>
                            <i class='la la-remove'></i>
                            <span>Disconnect</span>
                        </span>
                    </a>";
        })->rawColumns([ 'action', 'user', 'website'])->make(true);
    }
}
