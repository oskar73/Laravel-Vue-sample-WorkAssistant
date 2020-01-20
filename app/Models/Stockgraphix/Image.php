<?php

namespace App\Models\Stockgraphix;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $connection = 'mysql5';
    protected $table = 'images';

    public $timestamps = false;

    public function stock(){
		return $this->hasMany('App\Models\Stockgraphix\Stock', 'images_id')->orderBy('type','asc');
	}
}