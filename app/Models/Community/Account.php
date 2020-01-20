<?php

namespace App\Models\Community;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'users';
}