<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaletteCategory extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function approvedPalettes(): HasMany
    {
        return $this->hasMany(Palette::class, 'category_id')->where('status', 1);
    }
}
