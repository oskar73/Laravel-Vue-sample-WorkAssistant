<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;

class WebsiteBaseModel extends Model
{
    protected $connection = 'mysql2';

    public function scopeOf($query, $websiteId)
    {
        return $query->where('web_id', $websiteId);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
