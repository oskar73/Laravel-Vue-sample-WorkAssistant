<?php

namespace App\Http\Controllers\User\Domain;

use App\Http\Controllers\User\UserController;
use App\Integration\Paypal;
use App\Models\Domain;
use App\Models\DomainPrice;
use App\Models\Error;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class PaymentController extends UserController
{
    public function __construct(Domain $model)
    {
        $this->model = $model;
    }

    public function paywithStripe(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'address' => 'required|max:191',
            'country' => 'required|max:191',
            'city' => 'required|max:191',
            'state' => 'required|max:191',
            'zipcode' => 'required|max:191',
        ]);
        if ($validation->fails()) {
            return response()->json(['status' => 2, 'data' => $validation->errors()]);
        }
        $stripe = option("stripe", null);
        $stripe_sk = optional($stripe)['secret'];


        $stripe = new \Stripe\StripeClient(
            $stripe_sk
        );

        $domain = Session::get("pickDomain");
        $duration = Session::get("duration");

        //
        $response = $this->model->searchList($domain);
        if ($response['status'] == 0) {
            return response()->json(['status' => 0, 'data' => $response['result']]);
        }
        $result = $response['result'][0];
        if ($result['Available'] == 'false') {
            return response()->json(['status' => 0, 'data' => ['That domain is unavailable now. Please pick another one.']]);
        }
        //
        $total = DomainPrice::where("Action", "register")
            ->where('tld', getDomainTld($domain))
            ->where('Duration', $duration)
            ->where('status', 1)
            ->first()
            ->sumPrice;

        $package = UserPackage::where("user_id", user()->id)
            ->where("status", "active")
            ->where("domain", "!=", 0)
            ->whereDoesntHave("domains")
            ->first();

        $discount = $package->domain ?? 0;
        $g_total = ($total - $discount) < 0 ? 0 : ($total - $discount);

        $customer = $stripe->customers->create([
            'email' => $request->email,
            'source' => $request->token,
            'name' => $request->name,
            'address' => [
                'line1' => $request->address,
                'postal_code' => $request->zipcode,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
            ],
        ]);

        $charge = $stripe->charges->create([
            'amount' => $g_total * 100,
            'customer' => $customer->id,
            'currency' => 'usd',
        ]);

        $order = new Order();
        $order->user_id = user()->id;
        $order->gateway = 'stripe';
        $order->discount_total = $discount;
        $order->onetime_total = $total;
        $order->recurrent_total = 0;
        $order->total = $g_total;
        $order->total_qty = 1;
        $order->save();

        $resp = $this->model->registerDomain($domain, $duration);

        if ($resp['status'] == 0) {
            return response()->json(['status' => 0, 'data' => $resp['result']]);
        }

        $registerResult = $resp['result'][0];

        $created = $this->model->insertDomain($registerResult, $package->id ?? null);

        $order_item = new OrderItem();
        $order_item->user_id = user()->id;
        $order_item->order_id = $order->id;
        $order_item->order_item_id = guid();
        $order_item->recurrent = 0;
        $order_item->product_type = "domainRegister";
        $order_item->product_id = $created->id;
        $order_item->product_detail = $created;
        $order_item->price = $total;
        $order_item->quantity = 1;
        $order_item->sub_total = $total;
        $order_item->paid = 1;
        $order_item->agreement_id = $order->id;
        $order_item->status = "active";
        $order_item->save();

        $transaction = new Transaction();
        $transaction->user_id = user()->id;
        $transaction->charge_id = $charge->id;
        $transaction->agreement_id = $order->id;
        $transaction->gateway = 'stripe';
        $transaction->amount = $g_total;
        $transaction->refunded = 0;
        $transaction->recurrent = 0;
        $transaction->save();

        $transaction->makeInvoice();

        $dnsResult = 0;
        if ($request->dns == 1) {
            $dnsResult = $this->model->addRecord($created);
        }

        return response()->json(['status' => 1, 'dnsResult' => $dnsResult, 'registerResult' => $registerResult]);
    }

    public function paywithPaypal(Request $request)
    {
        $domain = Session::get("pickDomain");
        $duration = Session::get("duration");

        $total = DomainPrice::where("Action", "register")
            ->where('tld', getDomainTld($domain))
            ->where('Duration', $duration)
            ->where('status', 1)
            ->first()
            ->sumPrice;

        $package = UserPackage::where("user_id", user()->id)
            ->where("status", "active")
            ->where("domain", "!=", 0)
            ->whereDoesntHave("domains")
            ->first();

        $discount = $package->domain ?? 0;
        $g_total = ($total - $discount) < 0 ? 0 : ($total - $discount);

        $domainData['domain'] = $domain;
        $domainData['duration'] = $duration;
        $domainData['total'] = $total;
        $domainData['package'] = $package->id ?? 0;
        $domainData['discount'] = $discount;
        $domainData['g_total'] = $g_total;
        $domainData['dns'] = $request->dns ? 1 : 0;

        $paypal = new Paypal();
        $provider = $paypal->getProvider();
        $data = [];

        $orderId = guid();

        $data['items'] = [
            [
                'name' => 'Domain Register',
                'price' => $g_total,
                'desc' => "Domain Name:" . $domain . ", Duration: " . $duration,
                'qty' => 1,
            ],
        ];
        $data['invoice_id'] = $orderId . "--" . "0";
        $data['invoice_description'] = "Domain Register. " . "Domain Name:" . $domain . ", Duration: " . $duration;
        $data['return_url'] = route('user.domain.paywithPaypalExecute', "success");
        $data['cancel_url'] = route('user.domain.paywithPaypalExecute', "cancel");
        $data['total'] = $g_total;

        Session::put("paypalDomainRegisterData" . $data['invoice_id'], $data);

        Session::put("paypalDomainRegisterDomain" . $orderId, $domainData);
        $response = $provider->setExpressCheckout($data, false);

        return redirect($response['paypal_link']);
    }

    public function paywithPaypalExecute(Request $request, $status)
    {
        if ($status == 'cancel') {
            return redirect()->route('user.domain.search')->with('info', "Canceled");
        } elseif ($status == 'success') {
            DB::beginTransaction();

            try {
                $token = $request->token;
                $payerId = $request->PayerID;

                $paypal = new Paypal();
                $provider = $paypal->getProvider();
                $checkoutDetail = $provider->getExpressCheckoutDetails($token);

                $invoice_id = $checkoutDetail['INVNUM'];
                $invoice_nums = explode("--", $invoice_id);

                $data = Session::get("paypalDomainRegisterData" . $invoice_id);
                $domainData = Session::get("paypalDomainRegisterDomain" . $invoice_nums[0]);

                $domain = $domainData['domain'];
                $duration = $domainData['duration'];
                $total = $domainData['total'];
                $package = UserPackage::where("user_id", user()->id)
                    ->where("status", "active")
                    ->where("domain", "!=", 0)
                    ->whereId($domainData['package'])
                    ->whereDoesntHave("domains")
                    ->first();

                $discount = $package->domain ?? 0;
                $g_total = ($total - $discount) < 0 ? 0 : ($total - $discount);

                if ($g_total != $data['total']) {
                    return redirect()->route('user.domain.search')->with('error', 'Package is already used but you applied discount using that package.');
                }

                $response = $this->model->searchList($domain);

                info("domain search list response");
                info($response);

                if ($response['status'] == 0) {
                    return redirect()->route('user.domain.search')->with('error', 'Sorry, that domain is unavailable now. Please try to pick the another one.');
                }

                $result = $response['result'][0];
                info("domain search response result");
                info($result);
                if ($result['Available'] == 'false') {
                    return redirect()->route('user.domain.search')->with('error', 'Sorry, that domain is unavailable now. Please try to pick the another one.');
                }

                $response = $provider->doExpressCheckoutPayment($data, $token, $payerId);
                info('doExpressCheckoutPayment response');
                info($response);

                $order = new Order();
                $order->user_id = user()->id;
                $order->gateway = 'paypal';
                $order->discount_total = $discount;
                $order->onetime_total = $total;
                $order->recurrent_total = 0;
                $order->total = $g_total;
                $order->total_qty = 1;
                $order->save();

                $resp = $this->model->registerDomain($domain, $duration);
                info('registerDomain response');
                info($resp);

                $registerResult = $resp['result'][0];

                $created = $this->model->insertDomain($registerResult, $package->id ?? null);
                info('insertDomain response');
                info($created);

                $order_item = new OrderItem();
                $order_item->user_id = user()->id;
                $order_item->order_id = $order->id;
                $order_item->order_item_id = guid();
                $order_item->recurrent = 0;
                $order_item->product_type = "domainRegister";
                $order_item->product_id = $created->id;
                $order_item->product_detail = $created;
                $order_item->price = $total;
                $order_item->quantity = 1;
                $order_item->sub_total = $total;
                $order_item->paid = 1;
                $order_item->agreement_id = $order->id;
                $order_item->status = "active";
                $order_item->save();

                $transaction = new Transaction();
                $transaction->user_id = user()->id;
                $transaction->charge_id = $response['PAYMENTINFO_0_TRANSACTIONID'];
                $transaction->agreement_id = $order->id;
                $transaction->gateway = 'paypal';
                $transaction->amount = $g_total;
                $transaction->refunded = 0;
                $transaction->recurrent = 0;
                $transaction->save();

                $transaction->makeInvoice();
                info('insertDomain response');

                if ($domainData['dns'] == 1) {
                    $result = $this->model->addRecord($created);
                    info("Domain Record Response");
                    info($result);

                    $result = $this->model->switchLocked('true', $created->name);
                    info("Domain Rocked Status Response");
                    info($result);

                    try {
                        \Http::get("//{$domain}");
                    } catch (\Exception $e) {
                        info("Initial Request failed");
                        info($e);
                    }
                }
                DB::commit();

                return redirect()->route('user.domainList.index')->with('success', "Successfully registered");
            } catch (\Exception $e) {
                DB::rollBack();
                info("paywithPaypalExecute Error");
                info($e);
                Error::create([
                    'location' => 'User/Domain/PaymentController::paywithPaypalExecute()',
                    'error' => json_encode($e->getMessage()),
                ]);

                return redirect()->route('user.domain.search')->with('error', "We are really sorry. Something went wrong. Please contact admin.");
            }
        }
    }
}
