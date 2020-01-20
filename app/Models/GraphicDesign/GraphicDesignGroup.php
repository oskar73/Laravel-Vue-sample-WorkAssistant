<?php

namespace App\Models\GraphicDesign;

use App\Models\BaseModel;

class GraphicDesignGroup extends BaseModel
{
    protected $table = 'graphic_design_groups';

    protected $guarded = ['id'];

    public $timestamps = false;
}
