<?php

namespace App\Models\Module;

class EcommerceCustomer extends ModuleBaseModel
{
    protected $table = 'ecommerce_customers';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
