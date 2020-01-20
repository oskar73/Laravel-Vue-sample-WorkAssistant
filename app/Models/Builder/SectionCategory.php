<?php

namespace App\Models\Builder;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;

class SectionCategory extends BaseModel
{
    use Sluggable;

    protected $connection = 'mysql';

    protected $table = 'section_categories';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:6000';
        $rule['limit_per_page'] = 'required|integer';

        return $rule;
    }

    public function storeItem($request)
    {
        $this->name = $request->name;
        $this->description = $request->description;
        $this->status = $request->status ? 1 : 0;
        $this->recommended = $request->recommended ? 1 : 0;
        $this->limit_per_page = $request->limit_per_page ?? 1;
        $this->module = $request->module;
        $this->save();
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'category_id');
    }

    public function getSectionCountAttribute(): int
    {
        return count($this->sections);
    }

    public function activeSections()
    {
        return $this->hasMany(Section::class, 'category_id')->where("status", 1);
    }
}