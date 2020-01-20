<?php

namespace App\Models;

class BlogAdsType extends BaseModel
{
    protected $table = 'blog_ads_types';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function storeRule()
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:6000';
        $rule['width'] = 'required|integer|min:50';
        $rule['height'] = 'required|integer|min:50';
        $rule['title_char'] = 'required|integer';
        $rule['text_char'] = 'required|integer';

        return $rule;
    }
    public function storeItem($request)
    {
        $type = $this;
        $type->name = $request->name;
        $type->description = $request->description;
        $type->width = $request->width;
        $type->height = $request->height;
        $type->title_char = $request->title_char;
        $type->text_char = $request->text_char;
        $type->status = $request->status? 1:0;
        $type->save();

        return $type;
    }
}
