<?php

namespace App\Models;

class WebsiteModule extends SiteBaseModel
{
    protected $connection = 'mysql2';
    protected $table = 'website_modules';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
