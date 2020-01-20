<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HomeSlider extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title','description','status','button','link'];

    public function storeRule($request):array
    {
        if ($request->slider_id) {
            $rule['slider_id'] = 'integer';
        } else {
            $rule['image'] = 'required';
        }

        return $rule;
    }
}
