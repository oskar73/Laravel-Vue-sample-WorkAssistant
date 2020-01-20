<?php

namespace App\Models;

class DomainCustom extends BaseModel
{
    protected $connection = 'mysql';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'domain_customs';


    public function website()
    {
        return $this->belongsTo(\App\Models\Website::class, 'web_id')->withDefault();
    }
}
