<?php

namespace App\Models;

class UserEcommerceProduct extends BaseModel
{
    protected $connection = 'mysql2';
    protected $table = 'user_ecommerce_products';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
