<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Storage;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplate extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = 'email_templates';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($obj) {
            dirDel(storage_path("app/public/uploads/" . $obj->id));
        });
    }
    public function category()
    {
        return $this->belongsTo(EmailCategory::class, 'category_id')->withDefault();
    }

    public function storeRule($request)
    {
        if ($request->template_id) {
            $rule['template_id'] = 'required|exists:email_templates,id';
        }
        $rule['category'] = 'required|exists:email_categories,id';
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'required|max:600';

        return $rule;
    }
    public function storeItem($request)
    {
        $item = $this;
        $item->category_id = $request->category;
        $item->name = $request->name;
        $item->description = $request->description;
        if ($request->template_id) {
            $item->status = $request->status? 1: 0;
        } else {
            $item->status = 0;
        }
        $item->new = $request->new? 1: 0;
        $item->featured = $request->featured? 1: 0;
        $item->save();

        if ($request->thumbnail) {
            // $this->clearMediaCollection('thumbnail')
            //     ->addMedia($request->thumbnail)
            //     ->usingFileName(guid() . ".jpg")
            //     ->toMediaCollection('thumbnail');
            $this->clearMediaCollection("thumbnail")
                ->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('thumbnail');
        }

        return $item;
    }

    public function updateBody($request)
    {
        $item = $this;
        if ($this->body == null) {
            $body = $request->body;
            if ($body != null && $request->fileNames != null) {
                foreach (json_decode($request->fileNames) as $name => $fileName) {
                    $route = Storage::url(config("custom.storage_name.email") . '/' . $this->id . "/" .$fileName);

                    $cases = [
                        "src=\"images/{$name}\"",
                        "src='images/{$name}'",
                        "src=\"./images/{$name}\"",
                        "src='./images/{$name}'",
                        "url(\"images/{$name}\")",
                        "url('images/{$name}')",
                        "url(\"./images/{$name}\")",
                        "url('./images/{$name}')",
                    ];
                    $output = [
                        "src=\"{$route}\"",
                        "src='{$route}'",
                        "src=\"{$route}\"",
                        "src='{$route}'",
                        "url(\"{$route}\")",
                        "url('{$route}')",
                        "url(\"{$route}\")",
                        "url('{$route}')",
                    ];

                    foreach ($cases as $key => $case) {
                        $body = str_replace($case, $output[$key], $body);
                    }
                    //                    $body =  str_replace("src=\"images/{$name}\"", "src='{$route}'", $body);
                    //                    $body =  str_replace("src=\"./images/{$name}\"", "src='{$route}'", $body);
                    //                    $body =  str_replace("url('images/{$name}')", "src='{$route}'", $body);
                    //                    $body =  str_replace("url('./images/{$name}')", "src='{$route}'", $body);
                }
            }
            //            $body = remove_between('<!--', '-->',$body);
        } else {
            $body = $request->tem_body;

            $body = str_replace("<p>&nbsp;</p>", "", $body);
            $body = str_replace("<p></p>", "", $body);

            //            $body = remove_between('<!--', '-->',$body);
        }
        $item->body = $body;
        $item->save();

        return $item;
    }
    public function getDatatable($status)
    {
        switch ($status) {
            case 'all':
                $templates = $this::with('category');

                break;
            case 'active':
                $templates = $this::with('category')->where('status', 1);

                break;
            case 'inactive':
                $templates = $this::with('category')->where('status', 0);

                break;
        }

        return Datatables::of($templates)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
        })->addColumn('category', function ($row) {
            return $row->category->name;
        })->addColumn('review', function ($row) {
            return '4';
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
                    <a href="' . route('admin.email.template.edit', $row->id) . '" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-id="'.$row->id.'">
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
        })->rawColumns(['checkbox','status','category', 'action'])->make(true);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(40)
            ->height(60)
            ->sharpen(10)
            ->nonQueued();
    }
}
