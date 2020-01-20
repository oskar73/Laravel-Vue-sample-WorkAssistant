<?php

namespace App\Models\Builder;

use App\Models\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Section extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'sections';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
    }

    public function image()
    {
        return $this->getFirstMediaUrl('image');
    }

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function getDefaultImage()
    {
        return asset("assets/img/default-300x200.jpg");
    }

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['data'] = 'required';

        return $rule;
    }

    public function updateRule($request)
    {
        $rule['category'] = 'required|exists:section_categories,id';
        $rule['name'] = 'required|max:45';
        $rule['data'] = 'required';

        return $rule;
    }
    public function storeItem($request, $category)
    {
        $this->category_id = $category->id;
        $this->status = $request->status? 1:0;
        $this->data = $request->data;
        $this->name = $request->name;
        $this->save();

        if ($request->image) {
            $this->addMedia($request->image)
                ->usingFileName(guid() . "." . $request->image->getClientOriginalExtension())
                ->toMediaCollection('image');
        }

        return $this;
    }



    public function updateItem($request)
    {
        $this->update([
            'category_id' => $request->category,
            'status' => $request->status? 1:0,
            'data' => $request->data,
            'name' => $request->name,
        ]);

        if ($request->image) {
            $this->clearMediaCollection("image")
                ->addMedia($request->image)
                ->usingFileName(guid() . "." . $request->image->getClientOriginalExtension())
                ->toMediaCollection('image');
        }

        return $this;
    }

    public function category()
    {
        return $this->belongsTo(SectionCategory::class, 'category_id');
    }
}
