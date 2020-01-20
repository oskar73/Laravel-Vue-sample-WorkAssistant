<?php

namespace App\Models;

class WebsiteAvailableHour extends BaseModel
{
    protected $connection = 'mysql2';
    protected $table = "available_hours";

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function weekday()
    {
        return $this->belongsTo(WebsiteAvailableWeekday::class, "weekday_id")->withDefault();
    }
}
