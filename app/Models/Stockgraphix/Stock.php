<?php

namespace App\Models\Stockgraphix;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $connection = 'mysql5';
    protected $table = 'stock';
    public $timestamps = false;

    public function image() {
        return $this->belongsTo('App\Models\Stockgraphix\Images', 'images_id');
    }
}