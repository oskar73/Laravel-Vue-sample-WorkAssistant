<?php

namespace App\Models\Module;

class EcommerceOrderItem extends ModuleBaseModel
{
    protected $table = 'ecommerce_order_items';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(EcommerceOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(EcommerceSize::class, 'size_id');
    }

    public function color()
    {
        return $this->belongsTo(EcommerceColor::class, 'color_id');
    }

    public function variant()
    {
        return $this->belongsTo(EcommerceVariant::class, 'variant_id');
    }
}
