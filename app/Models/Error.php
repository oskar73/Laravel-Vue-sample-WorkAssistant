<?php

namespace App\Models;

class Error extends BaseModel
{
    protected $table = "errors";
    protected $guarded = ["id", "created_at", "updated_at"];
}
