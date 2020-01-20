<?php

namespace App\Models;

class ThemePalette extends BaseModel
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'data' => 'json',
    ];

    public function palette()
    {
        return $this->belongsTo(Palette::class, 'palette_id');
    }
}
