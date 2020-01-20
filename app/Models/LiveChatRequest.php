<?php

namespace App\Models;

class LiveChatRequest extends BaseModel
{
    protected $table = "live_chat_requests";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function rule()
    {
        $rule['service'] = 'required|exists:live_chat_services,id';
        $rule['email'] = 'required|email|max:191';
        $rule['name'] = 'nullable|max:191';
        $rule['phone'] = 'nullable|max:191';
        $rule['message'] = 'required|max:600';

        return $rule;
    }
    public function storeItem($request)
    {
        $item = $this;
        $item->service_id = $request->service;
        $item->name = $request->name;
        $item->message = $request->message;
        $item->phone = $request->phone;
        $item->email = $request->email;
        $item->device = getOS();
        $item->ip = $request->ip();
        $item->save();

        return $item;
    }
}
