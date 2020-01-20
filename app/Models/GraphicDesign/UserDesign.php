<?php

namespace App\Models\GraphicDesign;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class UserDesign extends BaseModel
{
    protected $table = 'user_graphic_designs';

    protected $guarded = ['id'];

    public const STORAGE_DISK = 's3-pub-bizinabox';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function design(): BelongsTo
    {
        return $this->belongsTo(GraphicDesign::class, "design_id")->withDefault();
    }

    public function graphic(): BelongsTo
    {
        return $this->belongsTo(Graphic::class, "graphic_id")->withDefault();
    }

    public function pairs(): HasMany
    {
        return $this->hasMany(UserDesign::class, "parent_id");
    }

    public function setAsEdited(): self
    {
        $this->downloadable = 0;
        $this->save();

        return $this;
    }

    public function getContent()
    {
        return $this->design_content;
    }

    public function setAsInProgress(): self
    {
        $this->downloadable = 2;
        $this->progress = 1;
        $this->save();

        return $this;
    }

    public function preview(): BelongsTo
    {
        return $this->belongsTo(UserDesignPreview::class, 'id', 'user_design_id')->withDefault();
    }

    public function savePairs()
    {

    }

    public function getEncryptedDesignContent(): string
    {
        return str_rot13(base64_encode($this->getContent()));
    }

    public function getDatatable($graphic_id, $user_id = null): JsonResponse
    {
        $designs = self::where("user_id", auth()->user()->id)
            ->whereNull('parent_id')
            ->with(['preview', 'design']);

        if($user_id){
            $designs = self::where("user_id", $user_id)
                ->whereNull('parent_id')
                ->with(['preview', 'design']);
        }

        if ($graphic_id != 'all') {
            $designs->where('graphic_id', $graphic_id);
        }

        return Datatables::of($designs)->addColumn('type', function ($row) {
            if ($row->design->premium == 1) {
                return '<span class="c-badge c-badge-premium">Premium</span>';
            } else {
                return '<span class="c-badge c-badge-info" >Free</span>';
            }
        })->addColumn('preview', function ($row) {
            return "<a href='" . route('user.graphics.preview', $row->hash) . "' target='_blank'><img src='{$row->preview->content}' class='w-150px h-cursor'></a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateString();
        })->editColumn('updated_at', function ($row) {
            return $row->updated_at->toDateString();
        })->addColumn('view_live', function ($row) {
            return "<a href='" . route('user.graphics.live') . "' class='btn btn-outline-info p-2'>View live</a>";
        })->editColumn('download1', function ($row) {
            return "<a href='" . route('user.graphics.download', $row->hash) . "' target='_blank' class='btn btn-outline-info btn-sm downloadFaviconTypeBtn mr-1'>Download PNG <i class='fa fa-download'></i></a>";;
        })->editColumn('download2', function ($row) {
            if ($row->downloadable == 1) {
                $middle = "<a href='" . route('user.graphics.download.package', $row->hash) . "' class='btn btn-outline-success btn-sm downloadPackageBtn' >Download Design Package <i class='fa fa-download'></i></a>";
            } elseif ($row->downloadable == 2) {
                $progress = number_format($row->progress, 2);
                $middle = "<p>Progress: $progress %</p><div class='progress progress_el' data-id='{$row->id}'><div class=\"progress-bar\" role=\"progressbar\" style=\"width: {$row->progress}%\" aria-valuenow=\"{$row->progress}\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div></div>";
            } else {
                $middle = "<a href='" . route('user.graphics.download.package', $row->hash) . "' class='btn btn-outline-info btn-sm downloadPackageBtn' >Download Design Package <i class='fa fa-download'></i></a>";
            }

            return "<div class='progress_area' data-id='{$row->id}'>{$middle}</div>";
        })->addColumn('edit', function ($row) {
            return '
                    <a href="' . route('user.graphics.edit', $row->hash) . '" class="btn btn-outline-info btn-sm" title="Edit">
                          <i class="la la-edit"></i>  Edit
                    </a>';
        })->addColumn('action', function ($row) {
            return '
                    <a href="' . route('user.graphics.delete', $row->hash) . '" class="btn btn-outline-danger btn-sm deleteBtn" title="Delete">
                         <i class="la la-remove"></i>   Delete
                    </a>';
        })->rawColumns(['preview', 'download1', 'download2', 'type', 'view_live', 'edit', 'action'])
            ->make(true);
    }

    public function getAdminDataTable($graphic_id, $user_id = null): JsonResponse
    {
        $designs = self::whereNull('parent_id')
            ->with(['preview', 'design']);

        if($user_id){
            $designs = self::where("user_id", $user_id)
                ->whereNull('parent_id')
                ->with(['preview', 'design']);
        }

        if ($graphic_id != 'all') {
            $designs->where('graphic_id', $graphic_id);
        }

        return Datatables::of($designs)->addColumn('type', function ($row) {
            if ($row->design->premium == 1) {
                return '<span class="c-badge c-badge-premium">Premium</span>';
            } else {
                return '<span class="c-badge c-badge-info" >Free</span>';
            }
        })
        ->addColumn('preview', function ($row) {
            return "<a href='" . route('user.graphics.preview', $row->hash) . "' target='_blank'><img src='{$row->preview->content}' class='w-150px h-cursor'></a>";
        })->addColumn('version', function ($row) {
            return $row->version;
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateString();
        })->editColumn('name', function ($row) {
            return $row->user->name;
        })->addColumn('live_edit', function ($row) {
            return "<a href='" . route('user.graphics.live') . "' class='btn btn-outline-info p-2'>View live</a>";
        })->editColumn('download', function ($row) {
            return "<a href='" . route('user.graphics.download', $row->hash) . "' target='_blank' class='btn btn-outline-info btn-sm downloadFaviconTypeBtn mr-1'>Download PNG <i class='fa fa-download'></i></a>";;
        })->addColumn('edit', function ($row) {
            return '
                    <a href="' . route('user.graphics.edit', $row->hash) . '" class="btn btn-outline-info btn-sm" title="Edit">
                          <i class="la la-edit"></i>  Edit
                    </a>';
        })->rawColumns(['preview', 'live_edit', 'download', 'type', 'edit'])
            ->make(true);
    }
}
