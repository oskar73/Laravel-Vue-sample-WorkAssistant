<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection = 'mysql4';
    protected $table = 'Wo_Users';
    protected $primaryKey = 'user_id';

    public $timestamps = false;
}