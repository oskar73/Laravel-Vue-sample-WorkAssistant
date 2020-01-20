<?php

namespace App\Models;

class PaymentContact extends BaseModel
{
    protected $table = 'payment_contacts';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
