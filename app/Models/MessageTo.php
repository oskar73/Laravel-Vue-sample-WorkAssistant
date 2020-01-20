<?php

namespace App\Models;

class MessageTo extends BaseModel
{
    protected $table = "message_to";

    protected $guarded = ["id", "created_at", "updated_at"];
}
