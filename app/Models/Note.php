<?php

namespace App\Models;

class Note extends BaseModel
{
    protected $table = "notes";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id")->withDefault();
    }
}
