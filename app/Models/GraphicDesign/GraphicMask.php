<?php

namespace App\Models\GraphicDesign;

use App\Models\BaseModel;


class GraphicMask extends BaseModel
{

    protected $table = 'graphic_masks';

    protected $guarded = ['id'];

    public function graphic()
    {
        return $this->belongsTo(Graphic::class);
    }

    public function storeItem($request, $graphic_id, $content, $content_id, $status = 1)
    {
        if ($request->mask_id == null) {
            $mask = $this;
        } else {
            $mask = $this->findorfail($request->mask_id);
        }

        $mask->graphic_id = $graphic_id;
        $mask->content = $content;
        $mask->content_id = $content_id;
        $mask->status = $status;

        $mask->save();

        return $mask;
    }
}
