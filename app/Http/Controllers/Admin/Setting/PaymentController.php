<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends AdminController
{
    public $array;

    public function __construct()
    {
    }
    public function index()
    {
        $gateway = option("gateway", []);
        $paypal = option("paypal", null);
        $stripe = option("stripe", null);

        return view(self::$viewDir . "setting.payment", compact("paypal", "stripe", 'gateway'));
    }
    public function rule($request)
    {
        $rule = [];
        if ($request->paypal) {
            $rule['paypal_api_username'] = 'required|max:255';
            $rule['paypal_api_password'] = 'required|max:255';
            $rule['paypal_api_secret'] = 'required|max:255';
            $rule['paypal_client_id'] = 'required|max:255';
            $rule['paypal_client_secret'] = 'required|max:255';
            $rule['paypal_partner_id'] = 'required|max:255';
            $rule['paypal_webhook_id'] = 'required|max:255';
            $rule['paypal_api_sandbox'] = 'required|in:sandbox,live';
        }
        if ($request->stripe) {
            $rule['stripe_public_key'] = 'required|max:255';
            $rule['stripe_secret_key'] = 'required|max:255';
            $rule['stripe_webhook_secret'] = 'required|max:255';
            $rule['stripe_webhook_secret_connect'] = 'required|max:255';
        }

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
        $gateway = [];
        if ($request->paypal) {
            array_push($gateway, "paypal");
        }
        if ($request->stripe) {
            array_push($gateway, "stripe");
        }
        option(['gateway' => $gateway]);

        if ($request->paypal) {
            $currentPaypal = option('paypal', null);
            $paypal['username'] = $request->paypal_api_username;
            $paypal['password'] = $request->paypal_api_password;
            $paypal['secret'] = $request->paypal_api_secret;
            $paypal['client_id'] = $request->paypal_client_id;
            $paypal['client_secret'] = $request->paypal_client_secret;
            $paypal['partner_id'] = $request->paypal_partner_id;
            $paypal['webhook_id'] = $request->paypal_webhook_id;
            $paypal['sandbox'] = $request->paypal_api_sandbox;
    
            option(['paypal' => $paypal]);
        }

        if ($request->stripe) {
            $stripe['public'] = $request->stripe_public_key;
            $stripe['secret'] = $request->stripe_secret_key;
            $stripe['webhook'] = $request->stripe_webhook_secret;
            $stripe['webhook_connect'] = $request->stripe_webhook_secret_connect;
    
            option(['stripe' => $stripe]);
        }

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }
}
