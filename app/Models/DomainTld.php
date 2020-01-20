<?php

namespace App\Models;

use Yajra\DataTables\DataTables;

/**
 * Class DomainTld.
 *
 * @package namespace App\Entities;
 */
class DomainTld extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'domain_tlds';

    public function getFirstYearPriceAttribute()
    {
        $price = $this->prices()->where('action', 'register')->where('status', 1)->orderBy('Duration', 'asc')->first();

        return $price->totalPrice ?? null;
    }

    public function prices()
    {
        return $this->hasMany(\App\Models\DomainPrice::class, 'tld', 'Name');
    }


    public function getDatatable($status = 0)
    {
        switch ($status) {
            case 0:
                $tlds = $this->with('prices')->select('*');

                break;

            case 1:
                $tlds = $this->with('prices')->where('status', 1)->select('*');

                break;

            case 2:
                $tlds = $this->with('prices')->where('status', 0)->select('*');

                break;

            case 3:
                $tlds = $this->with('prices')->where('recommend', 1)->orderBy("sortOrder", "DESC")->select('*');

                break;
        }

        return DataTables::of($tlds)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
        })->editColumn('Name', function ($row) {
            return $row->Name;
        })->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="c-badge c-badge-success hover-handle">Active</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>';
            } else {
                return '<span class="c-badge c-badge-danger hover-handle" >InActive</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>';
            }
        })->editColumn('recommend', function ($row) {
            if ($row->recommend == 1) {
                return '<span class="c-badge c-badge-success hover-handle">Recommended</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger origin-none d-none down-handle hover-box switchOne" data-action="unrecommend">Cancel?</a>';
            } else {
                return '<a href="javascript:void(0);" class="c-badge c-badge-success hover-vis hover-box switchOne" data-action="recommend">Recommended?</a>';
            }
        })->addColumn('price', function ($row) {
            return $row->prices()->where('Action', 'register')->orderBy('id')->first()->totalPrice ?? '';
        })->addColumn('action', function ($row) {
            return '<a href="'.route('admin.domainTld.show', $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>
                    <a href="'.route('admin.domainTld.edit', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'status', 'recommend', 'action'])->make(true);
    }

    public function switchStatus($request)
    {
        switch ($request->action) {
            case 'active':
                foreach ($request->ids as $id) {
                    $this->where('id', $id)->update(['status' => 1]);
                }

                break;
            case 'inactive':
                foreach ($request->ids as $id) {
                    $this->where('id', $id)->update(['status' => 0]);
                }

                break;
            case 'recommend':
                foreach ($request->ids as $id) {
                    $this->where('id', $id)->update(['recommend' => 1]);
                }

                break;
            case 'unrecommend':
                foreach ($request->ids as $id) {
                    $this->where('id', $id)->update(['recommend' => 0]);
                }

                break;
        }

        return true;
    }

    public function switchRule()
    {
        $rule['ids'] = 'required|array|min:1';
        $rule['action'] = 'in:active,inactive,recommend,unrecommend';

        return $rule;
    }
}
