<?php

namespace App\Models;

class Widget extends BaseModel
{
    protected $table = 'widgets';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(WidgetCategory::class, 'category_id')->withDefault();
    }
}