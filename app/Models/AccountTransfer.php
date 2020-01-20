<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTransfer extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'account_transfers';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
