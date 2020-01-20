<?php

namespace App\Models;

class Address extends BaseModel
{
    protected $table = "addresses";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    protected $appends = ["name"];

    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
