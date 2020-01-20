<?php

namespace App\Http\Controllers\Admin\Domain;

use App\Http\Controllers\Admin\AdminController;
use App\Integration\SSH;
use App\Models\Domain;
use App\Models\DomainConnect;
use Illuminate\Http\Request;

class DomainTransferController extends AdminController
{
    public function __construct(Domain $model)
    {
        $this->model = $model;
    }
    public function transfer()
    {
        return view(self::$viewDir.'domain.transfer');
    }
    public function connect()
    {
        return view(self::$viewDir.'domain.connect');
    }
    public function connectPost(Request $request)
    {
        $this->validate($request, [
            'domain' => 'required|unique:domain_connects,name|regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i',
        ]);

        //        $ssh = new SSH();
        //        $ssh->addAliasAndSSL($request->domain);

        $domain = new DomainConnect();
        $domain->user_id = user()->id;
        $domain->name = $request->domain;
        $domain->save();

        return back()->with("success", "Successfully connected!");
    }
    public function connectList()
    {
        if (request()->wantsJson()) {
            $domain = new DomainConnect();

            return $domain->getDatatable();
        }

        return view(self::$viewDir.'domain.connectList');
    }

    public function disconnect(Request $request)
    {
        try {
            $domain = DomainConnect::whereId($request->id)
                ->firstorfail();

            if ($domain->web_id) {
                return $this->jsonError(["This domain is being used by website {$domain->website->name}. In order to disconnect this domain, please change website's domain first."]);
            }
            //            $ssh = new SSH();
            //            $ssh->removeAliasAndSSL($request->domain);

            $domain->delete();

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
