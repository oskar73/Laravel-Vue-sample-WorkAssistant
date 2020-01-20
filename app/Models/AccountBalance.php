<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'account_balances';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
