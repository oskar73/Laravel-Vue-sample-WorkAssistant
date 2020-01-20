<?php

namespace App\Models;

class Subscription extends BaseModel
{
    protected $table = "subscriptions";

    protected $guarded = ["id", "created_at", "updated_at"];
}
