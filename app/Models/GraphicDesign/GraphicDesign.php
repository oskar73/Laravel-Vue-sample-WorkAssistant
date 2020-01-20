<?php

namespace App\Models\GraphicDesign;

use App\Models\BaseModel;
use App\Models\GraphicDesignCategory;
use App\Models\Tutorial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class GraphicDesign extends BaseModel
{
    protected $table = 'graphic_designs';

    protected $guarded = [];

    const STORAGE_DISK = 's3-pub-bizinabox';
    const EXTENSION = 'svg';
    const DIRECTORY = 'designs';
    const PREVIEW_DIRECTORY = 'designs/previews/';
    const PREVIEW_EXTENSION = 'png';

    protected $appends = ['preview'];

    public function getPreviewAttribute(): string
    {
        return Storage::disk(self::STORAGE_DISK)->url(self::PREVIEW_DIRECTORY.$this->hash.".".self::PREVIEW_EXTENSION);
    }

    public function graphic()
    {
        return $this->belongsTo(Graphic::class, 'graphic_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(GraphicCategory::class, 'graphic_design_categories', 'design_id', 'category_id');
    }

    public function pairs(): BelongsToMany
    {
        return $this->belongsToMany(GraphicDesign::class, 'graphic_design_groups', 'owner_id', 'pair_id');
    }

    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class, "tutorial_id")->where('status', 1)->withDefault();
    }

    public function getContentAttribute(): string
    {
        return Storage::disk('s3-pub-bizinabox')->get($this->path);
    }

    public function updateItem($request): self
    {
        if ($request->preview_image) {
            $image = json_decode($request->preview_image)->output->image;
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
            $image = base64_decode($image);
            $name = $this->hash . ".png";
            Storage::disk(self::STORAGE_DISK)->put(self::PREVIEW_DIRECTORY . $name, $image);
            $this->preview = Storage::disk(self::STORAGE_DISK)->url(self::PREVIEW_DIRECTORY . $name);
        }

        $this->name = $request->name;
        $this->status = $request->status ? true : false;
        $this->premium = $request->premium ? 1 : 0;
        $this->recommend = $request->recommend ? 1 : 0;
        $this->order = $request->order;
        $this->global_order = $request->global_order;
        $this->tutorial_id = $request->tutorial;
        $this->save();

        $pair_ids = collect($request->pair_ids ?? [])->filter(function ($item, $key) use ($request) {
            return ! empty($item);
        })->all();

        GraphicDesignGroup::where('owner_id', $this->id)->delete();
        foreach ($pair_ids as $pair_id) {
            GraphicDesignGroup::updateOrCreate([
                'owner_id' => $this->id,
                'pair_id' => $pair_id,
            ]);
        }

        GraphicDesignCategory::where('design_id', $this->id)->delete();
        foreach ($request->category_ids as $category_id) {
            GraphicDesignCategory::updateOrCreate(
                ['design_id' => $this->id, 'category_id' => $category_id],
                ['status' => true],
            );
        }

        return $this;
    }

    public function deleteItem()
    {
        Storage::disk(self::STORAGE_DISK)->delete($this->path);

        $this->delete();
    }

    public function getDatatable($status)
    {
        switch ($status) {
            case 'all':
                $designs = $this::with('graphic');

                break;
            case 'active':
                $designs = $this::with('graphic')
                    ->where('status', true);

                break;
            case 'inactive':
                $designs = $this::with('graphic')
                    ->where('status', false);

                break;
        }

        return Datatables::of($designs)->addColumn('category', function ($row) {
            $result = '';
            if ($row->graphic) {
                $result .= "<div class='my-1'><a href='#' class='c-badge c-badge-success'>" . $row->graphic->title . "</a></div>";
            }
            return $result;
        })->addColumn('preview', function ($row) {
            return "<a class='tw-flex tw-justify-center' href='{$row->preview}' target='_blank'><img src='{$row->preview}' class='maxw-200'><a/>";
        })->editColumn('premium', function ($row) {
            $result = '';
            if ($row->premium == 1) {
                $result .= '<span class="c-badge c-badge-success m-1">Premium</span><br>';
            }
            if ($row->recommend == 1) {
                $result .= '<span class="c-badge c-badge-info m-1">Recommended</span>';
            }

            return $result;
        })->editColumn('status', function ($row) {
            if ($row->status == true) {
                return '<span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="' . route('admin.graphics.design.switch', $row->id) . '" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchBtn" data-action="inactive">Inactive?</a>';
            } else {
                return '<span class="c-badge c-badge-danger hover-handle" >InActive</span>
                        <a href="' . route('admin.graphics.design.switch', $row->id) . '" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchBtn" data-action="active">Active?</a>';
            }
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateString();
        })->addColumn('download', function ($row) {
            return "<a href='" . route('admin.graphics.design.download', $row->hash) . "'><i class='fa fa-download text-primary'></i></a>";
        })->addColumn('live_edit', function ($row) {
            return '<a href="' . route('graphics.choose', $row->hash) . '" class="btn m-btn--sm m-btn--square btn-outline-info" target="_blank">
                        Test in Editor
                    </a>';
        })->addColumn('action', function ($row) {
            return '

                    <a href="' . route('admin.graphics.design.edit', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Edit">
                            <i class="la la-edit"></i>
                    </a>
                    <a href="' . route('admin.graphics.design.delete', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3 deleteBtn" title="Delete">
                            <i class="la la-remove"></i>
                    </a>';
        })->rawColumns(['category', 'status', 'download', 'preview', 'premium', 'action', 'live_edit'])
            ->make(true);
    }
}
