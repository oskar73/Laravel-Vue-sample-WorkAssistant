<?php

namespace App\Http\Controllers\User\Domain;

use App\Http\Controllers\User\UserController;
use App\Integration\SSH;
use App\Models\Domain;
use App\Models\DomainConnect;
use App\Models\DomainTld;
use Illuminate\Http\Request;

class DomainTransferController extends UserController
{
    public function __construct(Domain $model)
    {
        $this->model = $model;
    }
    public function transfer()
    {
        $recommends = DomainTld::with('prices')->where('status', 1)->where('recommend', 1)->where('IsApiRegisterable', 'true')->orderBy('sortOrder')->select('id', 'Name')->get();

        return view(self::$viewDir.'domain.transfer', compact('recommends'));
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
    public function disconnect(Request $request)
    {
        try {
            $domain = DomainConnect::whereId($request->id)
                ->my()
                ->firstorfail();
            //
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
