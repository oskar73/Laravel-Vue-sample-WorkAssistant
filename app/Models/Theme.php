<?php

namespace App\Models;

use App\Traits\HasUniqueValue;

class Theme extends BaseModel
{
    use HasUniqueValue;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'data' => 'json',
    ];

    const CUSTOM_VALIDATION_MESSAGE = [
        'category_id.required' => 'Please choose category.',
        'name.required' => 'Please insert name.',
    ];

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:45';
        $rule['category_id'] = 'integer';

        return $rule;
    }

    public function category()
    {
        return $this->belongsTo(ThemeCategory::class, 'category_id')->withDefault();
    }

    public function palettes()
    {
        return $this->hasMany(ThemePalette::class, 'theme_id');
    }
}
