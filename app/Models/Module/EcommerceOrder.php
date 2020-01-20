<?php

namespace App\Models\Module;

class EcommerceOrder extends ModuleBaseModel
{
    protected $table = 'ecommerce_orders';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function items()
    {
        return $this->hasMany(EcommerceOrderItem::class, 'order_id');
    }
}
