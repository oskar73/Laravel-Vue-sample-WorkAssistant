<?php

namespace App\Models;

use App\Traits\HasUniqueValue;
use Illuminate\Database\Eloquent\Model;

class Palette extends Model
{
    use HasUniqueValue;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
      'data' => 'json',
    ];
}
