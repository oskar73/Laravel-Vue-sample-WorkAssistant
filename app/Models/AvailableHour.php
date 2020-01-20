<?php

namespace App\Models;

class AvailableHour extends BaseModel
{
    protected $table = "available_hours";

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function weekday()
    {
        return $this->belongsTo(AvailableWeekday::class, "weekday_id")->withDefault();
    }
}
