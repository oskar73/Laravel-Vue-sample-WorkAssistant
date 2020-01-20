<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteHeader extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'headers';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
