<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Validator;

class ThirdPartyController extends AdminController
{
    public $array;

    public function __construct()
    {
    }
    public function index()
    {
        $ssh = optional(option("ssh", []));
        $namecheap = optional(option("namecheap", []));
        $nocaptcha = optional(option("nocaptcha", []));

        return view(self::$viewDir . "setting.third-party", compact("ssh", "namecheap", "nocaptcha"));
    }
    public function rule($request)
    {
        $rule = [];
        $rule['namecheap_sandbox'] = "required|in:1,0";
        $rule['nocaptcha_secret'] = "nullable|string|max:191";
        $rule['nocaptcha_site_key'] = "nullable|string|max:191";

        return $rule;
    }
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rule($request));

        if ($validation->fails()) {
            return response()->json([
                'status' => 0,
                'data' => $validation->errors(),
            ]);
        }

        $old = optional(option("ssh", []));
        $ssh['domain'] = $request->ssh_domain;
        $ssh['root_domain'] = $request->ssh_root_domain;
        $ssh['ip'] = $request->ssh_ip;
        $ssh['key'] = $request->ssh_key ?? $old['key'];

        option(['ssh' => $ssh]);

        $namecheap['sandbox'] = $request->namecheap_sandbox == 1?true:false;
        $namecheap['user'] = $request->namecheap_user;
        $namecheap['key'] = $request->namecheap_key;
        $namecheap['ip'] = $request->namecheap_ip;

        option(['namecheap' => $namecheap]);

        $nocaptcha['secret'] = $request->nocaptcha_secret;
        $nocaptcha['sitekey'] = $request->nocaptcha_site_key;

        option(['nocaptcha' => $nocaptcha]);

        option(['socket_server' => $request->socket_server]);
        option(['mailcow_apikey' => $request->mailcow_apikey]);
        option(['mailcow_server' => $request->mailcow_server]);
        option(['unsplash_api_key' => $request->unsplash_api_key]);
        option(['google_map_key' => $request->google_map_key]);

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }
}
