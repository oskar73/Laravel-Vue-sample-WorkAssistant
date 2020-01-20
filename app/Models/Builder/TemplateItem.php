<?php

namespace App\Models\Builder;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;

class TemplateItem extends BaseModel
{
    use Sluggable;

    protected $table = 'template_items';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
