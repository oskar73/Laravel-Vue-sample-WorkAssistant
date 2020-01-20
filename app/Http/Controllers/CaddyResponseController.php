<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DomainConnect;
use App\Models\DomainCustom;
use Illuminate\Http\Request;

class CaddyResponseController extends Controller
{
    public function index(Request $request)
    {
        \Log::info("domain authorize request:" . $request->domain);

        $check1 = Domain::where("name", $request->domain)->count();
        if ($check1) {
            return response("Domain Authorized");
        } else {
            $check2 = DomainConnect::where("name", $request->domain)->count();

            if ($check2) {
                return response("Domain Authorized");
            } else {
                $check3 = DomainCustom::where("name", $request->domain)->count();

                if ($check3) {
                    return response("Domain Authorized");
                }
            }
        }

        abort(503);
    }
}
