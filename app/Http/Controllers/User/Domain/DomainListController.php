<?php

namespace App\Http\Controllers\User\Domain;

use App\Http\Controllers\User\UserController;
use App\Integration\Paypal;
use App\Models\Domain;
use App\Models\DomainConnect;
use App\Models\DomainPrice;
use App\Models\Error;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Session;
use Validator;

class DomainListController extends UserController
{
    public function __construct(Domain $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->ajax()) {
            if (request()->type == 'purchased') {
                $domains = $this->model->where('user_id', user()->id)
                    ->select("id", "name", "registered", "transfered", "whoisguardEnable", "expired_at", "pointed")
                    ->get();

                $view = view('components.user.domainListTable', compact("domains"))->render();

                return response()->json(['status' => 1, 'data' => $view, 'count' => $domains->count()]);
            } elseif (request()->type == 'connected') {
                $domains = DomainConnect::my()
                    ->select(['id', 'web_id', 'user_id', 'name', 'pointed', 'data'])
                    ->get();

                $view = view('components.user.domainConnectTable', compact("domains"))->render();

                return response()->json(['status' => 1, 'data' => $view, 'count' => $domains->count()]);
            }
        }

        return view(self::$viewDir.'domainList.index');
    }
    public function show($id)
    {
        $domain = $this->model->findorfail($id);

        return view("user.domainList.show", compact("domain"));
    }
    public function getDetail($id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $resp = $domain->getDetail();
            if ($resp['status'] == 0) {
                return response()->json(['error' => 'true', 'message' => [$resp['result']]]);
            }

            return response()->json(['success' => 'true', 'data' => current($resp['result'])]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getLocked($id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $resp = $domain->getLocked();

            return response()->json(['status' => $resp['status'], 'data' => $resp['data']]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function switchAction(Request $request, $id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $status = $request->status;
            if ($request->action == 'isLocked') {
                $resp = $domain->switchLocked($status);
            } else {
                $resp = $domain->switchWhois($status);
            }

            return response()->json(['status' => $resp['status'], 'data' => $resp['result']]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getContact($id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $resp = $domain->getContact();
            if ($resp['status'] == 0) {
                return response()->json(['error' => 'true', 'message' => [$resp['result']]]);
            }

            return response()->json(['success' => 'true', 'data' => $resp['result']['Registrant']]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function updateContact(Request $request, $id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $validation = Validator::make($request->all(), $this->model->contactRule($request), $this->model::CONTACT_RULE_CUSTOM_MSG);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $resp = $domain->setContact($request);

            return response()->json($resp);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getDns(Request $request, $id)
    {
        try {
            $domain = $this->model->findorfail($id);

            $resp = $domain->getDns($request);

            return response()->json($resp);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function updateDns(Request $request, $id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $validation = Validator::make($request->all(), $this->model->updateDnsRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            $resp = $domain->updateDns($request->nameserver);

            return response()->json($resp);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function setDefaultDns(Request $request, $id)
    {
        try {
            $domain = $this->model->findorfail($id);

            $resp = $domain->setDefaultDns();

            return response()->json($resp);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getHosts($id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $resp = $domain->getHosts();
            if ($resp['status'] == 0) {
                return response()->json(['error' => 'true', 'message' => [$resp['result']]]);
            }
            
            $host = $resp['result']['host'];
            $attributes = [];

            if (isset($host['@attributes']) && is_array($host['@attributes'])) {
                $attributes[] = $host['@attributes'];
            }

            foreach ($host as $item) {
                if (isset($item['@attributes'])) {
                    $attributes[] = $item['@attributes'];
                }
            }

            return response()->json(['success' => 'true', 'data' => $attributes]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function setHosts(Request $request, $id)
    {
        try {
            $domain = $this->model->findorfail($id);
            $validation = Validator::make($request->all(), $this->model->setHostsRule($request), $this->model::SET_HOST_RULE_CUSTOM_MSG);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'message' => $validation->errors()]);
            }

            $resp = $domain->setHosts($request);

            return response()->json(['status' => 1, 'data' => $resp['data']]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function renew($id)
    {
        try {
            $domain = $this->model->findorfail($id);
            Session::put("renewDomain", $domain);
            $getRenew = $domain->getRenew();

            return response()->json(['status' => 1, 'data' => $getRenew]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function renewConfirm(Request $request)
    {
        try {
            $domain = Session::get("renewDomain");
            Session::put("renew_duration", $request->duration);
            $duration = $request->duration;
            $price = DomainPrice::where("tld", getDomainTld($domain->name))
                ->where("Action", "renew")
                ->where("Duration", $duration)
                ->where("status", 1)
                ->first();

            $gateway = option("gateway", []);

            $stripe = option("stripe", null);
            $stripe_pk = optional($stripe)['public'];

            $confirm = view("components.domain.renewUserConfirm", compact("duration", "domain", "price", "stripe_pk", "gateway"))->render();

            return response()->json(['status' => 1, 'data' => $confirm]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function renewWithStripe(Request $request)
    {
        try {
            $domain = Session::get("renewDomain");
            $duration = Session::get("renew_duration");

            $price = DomainPrice::where("tld", getDomainTld($domain->name))
                ->where("Action", "renew")
                ->where("Duration", $duration)
                ->where("status", 1)
                ->first();

            $response = $this->model->possibleRenew($domain, $duration);
            if ($response == 'false') {
                return response()->json(['status' => 0, 'data' => ['Sorry, something went wrong.']]);
            }

            $stripe = option("stripe", null);
            $stripe_sk = optional($stripe)['secret'];

            $stripe = new \Stripe\StripeClient(
                $stripe_sk
            );

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
                'amount' => $price->sumPrice * 100,
                'customer' => $customer->id,
                'currency' => 'usd',
            ]);

            $order = new Order();
            $order->user_id = user()->id;
            $order->gateway = 'stripe';
            $order->discount_total = 0;
            $order->onetime_total = $price->sumPrice;
            $order->recurrent_total = 0;
            $order->total = $price->sumPrice;
            $order->total_qty = 1;
            $order->save();

            $resp = $this->model->renewDomain($domain, $duration);

            if ($resp['status'] == 0) {
                return response()->json(['status' => 0, 'data' => $resp['result']]);
            }

            $order_item = new OrderItem();
            $order_item->user_id = user()->id;
            $order_item->order_id = $order->id;
            $order_item->order_item_id = guid();
            $order_item->recurrent = 0;
            $order_item->product_type = "domainRenew";
            $order_item->product_id = $domain->id;
            $order_item->product_detail = $resp['domain'];
            $order_item->quantity = 1;
            $order_item->price = $price->sumPrice;
            $order_item->sub_total = $price->sumPrice;
            $order_item->paid = 1;
            $order_item->agreement_id = $order->id;
            $order_item->status = "active";
            $order_item->save();

            $transaction = new Transaction();
            $transaction->user_id = user()->id;
            $transaction->charge_id = $charge->id;
            $transaction->agreement_id = $order->id;
            $transaction->gateway = 'stripe';
            $transaction->amount = $price->sumPrice;
            $transaction->refunded = 0;
            $transaction->recurrent = 0;
            $transaction->save();

            $transaction->makeInvoice();

            $renewResult = current($resp['result']);

            return response()->json(['status' => 1, 'renewResult' => $renewResult]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function renewWithPaypal(Request $request)
    {
        $domain = Session::get("renewDomain");
        $duration = Session::get("renew_duration");

        $price = DomainPrice::where("tld", getDomainTld($domain->name))
            ->where("Action", "renew")
            ->where("Duration", $duration)
            ->where("status", 1)
            ->firstorfail();

        $total = $price->sumPrice;

        $domainData['domain'] = $domain;
        $domainData['duration'] = $duration;
        $domainData['total'] = $total;

        $paypal = new Paypal();
        $provider = $paypal->getProvider();
        $data = [];

        $orderId = guid();

        $data['items'] = [
            [
                'name' => 'Domain Renew',
                'price' => $total,
                'desc' => "Domain Name:" . $domain . ", Renew Duration: " . $duration,
                'qty' => 1,
            ],
        ];
        $data['invoice_id'] = $orderId . "--" . "0";
        $data['invoice_description'] = "Domain Register. " . "Domain Name:" . $domain . ", Duration: " . $duration;
        $data['return_url'] = route('user.domainList.renewWithPaypalExecute', "success");
        $data['cancel_url'] = route('user.domainList.renewWithPaypalExecute', "cancel");
        $data['total'] = $total;

        Session::put("paypalDomainRenewData" . $data['invoice_id'], $data);

        Session::put("paypalDomainRenewDomain" . $orderId, $domainData);
        $response = $provider->setExpressCheckout($data, false);

        return redirect($response['paypal_link']);
    }
    public function renewWithPaypalExecute(Request $request, $status)
    {
        if ($status == 'cancel') {
            return redirect()->route('user.domainList.index')->with('info', "Canceled");
        } elseif ($status == 'success') {
            try {
                $token = $request->token;
                $payerId = $request->PayerID;

                $paypal = new Paypal();
                $provider = $paypal->getProvider();
                $checkoutDetail = $provider->getExpressCheckoutDetails($token);

                $invoice_id = $checkoutDetail['INVNUM'];
                $invoice_nums = explode("--", $invoice_id);

                $data = Session::get("paypalDomainRenewData".$invoice_id);
                $domainData = Session::get("paypalDomainRenewDomain".$invoice_nums[0]);

                $domain = $domainData['domain'];
                $duration = $domainData['duration'];
                $total = $domainData['total'];

                if ($total != $data['total']) {
                    return redirect()->route('user.domainList.index')->with('error', 'Sorry, Total prices doesn\'t match.');
                }

                $response = $this->model->possibleRenew($domain, $duration);

                if ($response == 'false') {
                    return redirect()->route('user.domainList.index')->with('error', "Sorry that domain is unavailable for renew for that duration.");
                }

                $response = $provider->doExpressCheckoutPayment($data, $token, $payerId);

                $order = new Order();
                $order->user_id = user()->id;
                $order->gateway = 'paypal';
                $order->discount_total = 0;
                $order->onetime_total = $total;
                $order->recurrent_total = 0;
                $order->total = $total;
                $order->total_qty = 1;
                $order->save();

                $resp = $this->model->renewDomain($domain, $duration);

                if ($resp['status'] == 0) {
                    return redirect()->route('user.domainList.index')->with('error', "Sorry soemthing went wrong. Please contact admin.");
                }

                $order_item = new OrderItem();
                $order_item->user_id = user()->id;
                $order_item->order_id = $order->id;
                $order_item->order_item_id = guid();
                $order_item->recurrent = 0;
                $order_item->product_type = "domainRenew";
                $order_item->product_id = $domain->id;
                $order_item->product_detail = $resp['domain'];
                $order_item->quantity = 1;
                $order_item->price = $total;
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
                $transaction->amount = $total;
                $transaction->refunded = 0;
                $transaction->recurrent = 0;
                $transaction->save();

                $transaction->makeInvoice();

                return redirect()->route('user.domainList.index')->with('success', "Successfully renewed!");
            } catch (\Exception $e) {
                Error::create([
                    'location' => 'User/Domain/PaymentController::paywithPaypalExecute()',
                    'error' => json_encode($e->getMessage()),
                ]);

                return redirect()->route('user.domainList.index')->with('error', "We are really sorry. Something went wrong. Please contact admin.");
            }
        }
    }
}
