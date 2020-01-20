<?php

namespace App\Models;

class Firewall extends BaseModel
{
    protected $table = 'firewall';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function storeRule($request)
    {
        $rule['name'] = 'nullable|string|max:191';
        $rule['whitelisted'] = 'required|in:0,1';
        if ($request->firewall_id) {
            $rule['ip_address'] = 'required|max:191|unique:firewall,ip_address,'.$request->firewall_id;
        } else {
            $rule['ip_address'] = 'required|max:191|unique:firewall,ip_address';
        }

        return $rule;
    }
    public function storeItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->ip_address = $request->ip_address;
        $item->whitelisted = $request->whitelisted;
        $item->save();

        return $item;
    }
}
