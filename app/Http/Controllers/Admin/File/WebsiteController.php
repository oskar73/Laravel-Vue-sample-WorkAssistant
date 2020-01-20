<?php

namespace App\Http\Controllers\Admin\File;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Website;
use Yajra\DataTables\DataTables;

class WebsiteController extends AdminController
{
    public function index()
    {
        if (request()->wantsJson()) {
            $websites = Website::query();

            return Datatables::of($websites)->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
            })->addColumn('storage', function ($row) {
                return $row->storageUsage();
            })->addColumn('action', function ($row) {
                return '<a href="'.route('admin.file.website.show', $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>
                    <a href="'.route('admin.file.website.edit', $row->id).'" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>';
            })->rawColumns(['checkbox', 'status', 'storage', 'action'])->make(true);
        }

        return view(self::$viewDir."file.website");
    }
}
