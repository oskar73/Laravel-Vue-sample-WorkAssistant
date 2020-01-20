<?php

namespace App\Models\GraphicDesign;

use App\Models\Logo\BaseModel;
use App\Models\Logo\ColorPalette;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ColorCategory extends BaseModel
{
    use HasFactory;
    use Sluggable;

    protected $table = 'color_categories';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function palettes()
    {
        return $this->hasMany(ColorPalette::class, 'category_id');
    }

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        if ($request->category_id) {
            $rule['category_id'] = 'integer';
        } else {
            $rule['gradient'] = 'required|in:0,1';
        }

        return $rule;
    }

    public function storeItem($request)
    {
        if ($request->category_id == null) {
            $category = $this;
            $category->gradient = $request->gradient;
        } else {
            $category = $this->findorfail($request->category_id);
        }
        $category->name = $request->name;
        $category->status = $request->status?1:0;
        $category->save();

        return $category;
    }
}
