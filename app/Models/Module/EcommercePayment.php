<?php

namespace App\Models\Module;

class EcommercePayment extends ModuleBaseModel
{
    protected $table = 'ecommerce_payments';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
