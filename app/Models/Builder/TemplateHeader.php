<?php

namespace App\Models\Builder;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;

class TemplateHeader extends BaseModel
{
    use Sluggable;
    protected $table = 'template_headers';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [
    ];

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:600';
        if ($request->header_id) {
            $rule['header_id'] = 'integer';
        }

        return $rule;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'header_id');
    }
}
