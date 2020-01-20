<?php

namespace App\Models;

class WidgetCategory extends BaseModel
{
    protected $table = 'widget_categories';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function items()
    {
        return $this->hasMany(Widget::class, 'category_id')->orderBy('order');
    }
}