<?php

namespace App\Models\Logo;

use App\Models\GraphicDesign\ColorCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorPalette extends Model
{
    use HasFactory;
    protected $table = 'color_palettes';
    protected $guarded = ['id', 'created_at', 'updated_at'];

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
        return $this->belongsTo(ColorCategory::class, 'category_id')->withDefault();
    }
}
