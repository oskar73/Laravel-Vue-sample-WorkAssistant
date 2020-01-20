<?php

namespace App\Http\Controllers\User\Domain;

use App\Http\Controllers\User\UserController;
use App\Models\Domain;
use App\Models\DomainPrice;
use App\Models\DomainTld;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Session;
use Validator;

class DomainController extends UserController
{
    public function __construct(Domain $model)
    {
        $this->model = $model;
    }
    public function search()
    {
        $recommends = DomainTld::with('prices')
            ->where('status', 1)
            ->where('recommend', 1)
            ->where('IsApiRegisterable', 'true')
            ->orderBy('sortOrder')
            ->select('id', 'Name')
            ->get();

        return view('user.domain.search', compact('recommends'));
    }
    public function check(Request $request)
    {
        try {
            $request->merge(['tld' => getDomainTld($request->domain), 'domainName' => getDomainName($request->domain)]);
            $validation = Validator::make($request->all(), $this->model->domainRule(), $this->model::VALIDATION_MSGS);

            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $response = $this->model->checkDomain($request);
            if ($response['status'] == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => $response['result'],
                ]);
            } else {
                $views = [];
                $key = 1;
                $domainArray = [];

                foreach ($response['result'] as $result) {
                    if ($result['Domain'] == $request->domain) {
                        $views[0] = view("components.domain.record", $result)->render()."<br><hr>";
                    } else {
                        $views[$key] = view("components.domain.record", $result)->render();
                        $key++;
                    }
                    $domainArray[getDomainTld($result['Domain'])] = $result;
                }
                Session::put('domainArray', $domainArray);

                return response()->json([
                    'views' => $views,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function loadMore()
    {
        try {
            if (! Session::has("domain")) {
                return response()->json(['status' => 0, 'data' => ['error']]);
            }
            $response = $this->model->loadMore();
            if ($response['status'] == 0) {
                return response()->json([
                    'status' => 0,
                    'data' => $response['result'],
                ]);
            } else {
                $views = [];
                $domainArray = Session::get('domainArray');
                foreach ($response['result'] as $result) {
                    $views[] = view("components.domain.record", $result)->render();

                    $domainArray[getDomainTld($result['Domain'])] = $result;
                }
                Session::put('domainArray', $domainArray);

                return response()->json([
                    'status' => 1,
                    'views' => $views,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function duration(Request $request)
    {
        try {
            if ($request->domain != null) {
                $domain = $request->domain;
                Session::put("pickDomain", $domain);
            } else {
                $domain = Session::get("pickDomain");
            }


            $duration = $this->model->duration($domain, 'register');
            if ($duration == false) {
                return response()->json([
                    'status' => 0,
                    'data' => ['Domain is not available'],
                ]);
            }
            $view = view("components.domain.duration", compact('duration'))->render();

            return response()->json([
                'status' => 1,
                'view' => $view,
                'domain' => $domain,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function setDuration(Request $request)
    {
        try {
            Session::put("duration", $request->duration);

            return response()->json([
                'status' => 1,
                'data' => $request->duration,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function setContact(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->contactRule($request), $this->model::CONTACT_RULE_CUSTOM_MSG);

            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            if ($request->saveThis) {
                $this->model->saveContact($request);
            }
            Session::put('contact', $request->all());

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
    public function getConfirm()
    {
        $package = UserPackage::where("user_id", user()->id)
            ->where("status", "active")
            ->where("domain", "!=", 0)
            ->whereDoesntHave("domains")
            ->first();

        $domain = Session::get("pickDomain");
        $duration = Session::get("duration");
        $tldRecord = Session::get("tldRecord");

        $total = DomainPrice::where("Action", "register")
            ->where('tld', getDomainTld($domain))
            ->where('Duration', $duration)
            ->where('status', 1)
            ->first()
            ->sumPrice;

        $discount = $package->domain ?? 0;

        $gateway = option("gateway", []);

        $stripe = option("stripe", null);
        $stripe_pk = optional($stripe)['public'];

        $confirm = view("components.domain.userDomainConfirm", compact("domain", "duration", "tldRecord", "total", "discount", "package", "stripe_pk", "gateway"))->render();

        return response()->json([
            'status' => 1,
            'data' => $confirm,
        ]);
    }
    public function getNow(Request $request)
    {
        try {
            $domain = Session::get("pickDomain");
            $duration = Session::get("duration");

            $package = UserPackage::where("user_id", user()->id)
                ->where("status", "active")
                ->where("domain", "!=", 0)
                ->whereDoesntHave("domains")
                ->first();

            $total = DomainPrice::where("Action", "register")
                ->where('tld', getDomainTld($domain))
                ->where('Duration', $duration)
                ->where('status', 1)
                ->first()
                ->sumPrice;

            $discount = $package->domain ?? 0;

            if (($total - $discount) > 0) {
                return response()->json(['status' => 0, 'data' => ['Sorry, you are not allowed to get this domain for free.']]);
            }
            $response = $this->model->searchList($domain);
            if ($response['status'] == 0) {
                return response()->json(['status' => 0, 'data' => $response['result']]);
            }
            $result = $response['result'][0];
            if ($result['Available'] == 'false') {
                return response()->json(['status' => 0, 'data' => ['That domain is unavailable now. Please pick another one.']]);
            }

            $resp = $this->model->registerDomain($domain, $duration);

            if ($resp['status'] == 0) {
                return response()->json(['status' => 0, 'data' => $resp['result']]);
            }

            $package->domain_get = 1;
            $package->save();

            $registerResult = $resp['result'][0];
            $created = $this->model->insertDomain($registerResult, $package->id);

            $dnsResult = 0;
            if ($request->dns == 1) {
                $dnsResult = $this->model->addRecord($created);
                //
                //                $ssh = new SSH();
                //                $ssh->addAliasAndSSL($domain);
            }

            return response()->json(['status' => 1, 'dnsResult' => $dnsResult, 'registerResult' => $registerResult]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function cnamecheck() {
        $created = $this->model->find(7);
        $dnsResult = $this->model->addRecord($created);
        dd($dnsResult);
    }
}
