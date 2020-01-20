<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EcommerceProduct extends ModuleBaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'ecommerce_products';

    protected $appends = [
        'thumbnail',
    ];

    public function getThumbnailAttribute(): string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    public function standardPrice(): HasOne
    {
        return $this->hasOne(EcommercePrice::class, 'product_id')->where('standard', 1)->withDefault();
    }

    public function sizes()
    {
        return $this->hasMany(EcommerceSize::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(EcommerceVariant::class, 'product_id');
    }

    public function prices()
    {
        return $this->hasMany(EcommercePrice::class, 'product_id');
    }

    public function colors()
    {
        return $this->hasMany(EcommerceColor::class, 'product_id');
    }
}
