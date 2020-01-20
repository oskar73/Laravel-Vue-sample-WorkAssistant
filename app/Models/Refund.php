<?php

namespace App\Models;

class Refund extends BaseModel
{
    protected $table = "refunds";

    protected $guarded = ["id", "created_at", "updated_at"];
}
