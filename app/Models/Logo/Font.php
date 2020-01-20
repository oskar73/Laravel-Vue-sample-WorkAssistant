<?php

namespace App\Models\Logo;

use App\Repositories\FontRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class Font extends Model
{
    protected $guarded = ['id'];

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('s3-pub-bizinabox')->url($this->public_path);
    }

    public function deleteItem()
    {
        Storage::disk('s3-pub-bizinabox')->delete($this->public_path);
        $repository = new FontRepository();
        $repository->removeCss($this->name, $this->extension);

        $this->delete();
    }

    public function getDatatable()
    {
        $fonts = $this::query();

        return Datatables::of($fonts)->editColumn('public_path', function ($row) {
            return "<a href='{$row->url}'>Download <i class='fa fa-download'></i></a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateString();
        })->addColumn('action', function ($row) {
            return '
                    <a href="' . route('admin.graphics.font.delete', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3 deleteBtn" title="Delete">
                            <i class="la la-remove"></i>
                    </a>';
        })->rawColumns(['public_path', 'action'])
            ->make(true);
    }
}
