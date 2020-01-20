<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Firewall;
use Illuminate\Http\Request;

class FirewallController extends AdminController
{
    public function __construct(Firewall $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $firewalls = $this->model->latest()->get();

            $whitelisted_ips = $firewalls->where("whitelisted", 1);
            $blacklisted_ips = $firewalls->where("whitelisted", 0);

            $whitelisted = view('components.admin.firewall', [
                'firewalls' => $whitelisted_ips,
                'selector' => "datatable-whitelisted",
            ])->render();
            $blacklisted = view('components.admin.firewall', [
                'firewalls' => $blacklisted_ips,
                'selector' => "datatable-blacklisted",
            ])->render();

            $count['whitelisted'] = $whitelisted_ips->count();
            $count['blacklisted'] = $blacklisted_ips->count();

            return response()->json([
                'status' => 1,
                'whitelisted' => $whitelisted,
                'blacklisted' => $blacklisted,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'setting.firewall');
    }
    public function store(Request $request)
    {
        $validation = \Validator::make($request->all(), $this->model->storeRule($request));

        if ($validation->fails()) {
            return response()->json(['status' => 0, 'data' => $validation->errors()]);
        }

        if ($request->firewall_id) {
            $this->model->findorfail($request->firewall_id)
                ->storeItem($request);
        } else {
            $this->model->storeItem($request);
        }

        return response()->json(['status' => 1, 'data' => 1]);
    }
}
