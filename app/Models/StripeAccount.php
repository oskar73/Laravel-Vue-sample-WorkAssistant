<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeAccount extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'stripe_accounts';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
