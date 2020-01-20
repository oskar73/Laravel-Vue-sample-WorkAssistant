<?php

namespace App\Models;

class LegalPage extends BaseModel
{
    protected $table = "legal_pages";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function storeRule($request)
    {
        $rule['title'] = "required|max:191";
        $rule['keywords'] = "max:191";
        $rule['description'] = "max:255";

        return $rule;
    }
    public function updateItem($request)
    {
        $item = $this;
        $item->title = $request->title;
        $item->keywords = $request->keywords;
        $item->description = $request->description;
        $item->body = $request->body;
        $item->save();

        return $item;
    }
}
