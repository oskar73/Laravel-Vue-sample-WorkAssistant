<?php

namespace App\Models;

class Announcement extends BaseModel
{
    protected $table = "announcements";

    protected $guarded = ['id', 'created_at', 'updated_at'];


    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function storeRule()
    {
        $rule['title'] = 'required|max:191';
        $rule['content'] = 'max:6000';

        return $rule;
    }
    public function storeItem($request)
    {
        if ($request->item_id == null) {
            $item = $this;
        } else {
            $item = $this->where('user_id', 0)->findorfail($request->item_id);
        }
        $item->title = $request->title;
        $item->content = $request->content;
        $item->status = $request->status?1:0;
        $item->save();

        return $item;
    }
}
