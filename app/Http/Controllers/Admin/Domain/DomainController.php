<?php

namespace App\Http\Controllers\Admin\Domain;

use App\Http\Controllers\Admin\AdminController;
use App\Integration\SSH;
use App\Models\Domain;
use App\Models\DomainTld;
use Illuminate\Http\Request;
use Session;
use Validator;

class DomainController extends AdminController
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

        return view(self::$viewDir.'domain.search', compact('recommends'));
    }
    public function check(Request $request)
    {
        try {
            $request->merge(['tld' => getDomainTld($request->domain), 'domainName' => getDomainName($request->domain)]);
            $validation = Validator::make($request->all(), $this->model->domainRule(), $this->model::VALIDATION_MSGS);

            if ($validation->fails()) {
                return response()->json(['error' => true, 'message' => $validation->errors()]);
            }

            $response = $this->model->checkDomain($request);
            if ($response['status'] == 0) {
                return response()->json([
                    'error' => true,
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
                return response()->json(['error' => true, 'result' => 'error']);
            }
            $response = $this->model->loadMore();
            if ($response['status'] == 0) {
                return response()->json([
                    'error' => true,
                    'result' => $response['result'],
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
                    'error' => true,
                    'message' => 'Domain is not available',
                ]);
            }
            $view = view("components.domain.duration", compact('duration'))->render();

            return response()->json([
                'view' => $view,
                'domain' => $domain,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function setDuration(Request $request)
    {
        try {
            Session::put("duration", $request->duration);

            return response()->json([
                'duration' => $request->duration,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
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

            $confirm = view("components.domain.confirm")->render();

            return response()->json([
                'view' => $confirm,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getNow(Request $request)
    {
        try {
            $domain = Session::get("pickDomain");
            $duration = Session::get("duration");

            $response = $this->model->searchList($domain);
            if ($response['status'] == 0) {
                return response()->json(['error' => true, 'result' => $response['result']]);
            }
            $result = $response['result'][0];
            if ($result['Available'] == 'false') {
                return response()->json(['error' => true, 'result' => ['That domain is unavailable now.']]);
            }

            $resp = $this->model->registerDomain($domain, $duration);

            if ($resp['status'] == 0) {
                return response()->json(['error' => true, 'message' => $resp['result']]);
            }

            $registerResult = $resp['result'][0];
            $created = $this->model->insertDomain($registerResult);

            if ($request->dns == 1) {
                $dnsResult = $this->model->addRecord($created);
                //
                //                $ssh = new SSH();
                //                $ssh->addAliasAndSSL($domain);
            }

            return response()->json(['result' => 'Successfully registered', 'dnsResult' => $dnsResult, 'registerResult' => $registerResult]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
