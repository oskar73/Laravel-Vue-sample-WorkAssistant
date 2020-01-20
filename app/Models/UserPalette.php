<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPalette extends Model
{
    protected $table = 'user_palettes';


    public function getSolidColor($field = null)
    {
        if (! $this->data) {
            return '';
        }
        $mid = json_decode($this->data);
        if (! $field) {
            return $mid;
        }

        return $mid->$field ?? '';
    }

    public function getPreview()
    {
        if ($this->gradient) {
            return $this->preview;
        } else {
            return "<div class='solid_preview_item'>
                        <span style='background-color:#".$this->getSolidColor("color1").";'></span>
                        <span style='background-color:#".$this->getSolidColor("color2").";'></span>
                        <span style='background-color:#".$this->getSolidColor("color3").";'></span>
                        <span style='background-color:#".$this->getSolidColor("color4").";'></span>
                        <span style='background-color:#".$this->getSolidColor("color5").";'></span>
                    </div>";
        }
    }
    public function category()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
