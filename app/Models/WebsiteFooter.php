<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteFooter extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'footers';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
