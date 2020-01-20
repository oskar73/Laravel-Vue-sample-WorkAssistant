<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalAccount extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'paypal_accounts';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
