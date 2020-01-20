<?php

namespace App\Models;

class WebsiteSubscriber extends SiteBaseModel
{
    protected $connection = 'mysql2';
    protected $table = 'subscribers';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
