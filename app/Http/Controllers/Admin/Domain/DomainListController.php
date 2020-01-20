<?php

namespace App\Http\Controllers\Admin\Domain;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Domain;
use App\Models\DomainPrice;
use Illuminate\Http\Request;
use Session;
use Validator;

class DomainListController extends AdminController
{
    public function __construct(Domain $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"), request()->get("user"));
        }

        return view(self::$viewDir.'domainList.index');
    }
    public function show($id)
    {
        $domain = $this->model->findorfail($id);

        return view(self::$viewDir."domainList.show", compact("domain"));
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

            return response()->json(['success' => 'true', 'view' => $getRenew]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function renewConfirm(Request $request)
    {
        try {
            $domain = Session::get("renewDomain");
            Session::put("renew_duration", $request->duration);
            $duration = $request->duration;
            $price = DomainPrice::where("tld", getDomainTld($domain->name))->where("Action", "renew")->where("Duration", $duration)->where("status", 1)->first();
            $confirm = view("components.domain.renewConfirm", compact("duration", "domain", "price"))->render();

            return response()->json(['success' => 'true', 'view' => $confirm]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function renewNow(Request $request)
    {
        try {
            $domain = Session::get("renewDomain");
            $duration = Session::get("renew_duration");

            $response = $this->model->possibleRenew($domain, $duration);
            if ($response == 'false') {
                return response()->json(['error' => true, 'result' => ['Sorry, something went wrong.']]);
            }

            $resp = $this->model->renewDomain($domain, $duration);

            if ($resp['status'] == 0) {
                return response()->json(['error' => true, 'message' => $resp['result']]);
            }

            $renewResult = current($resp['result']);

            return response()->json(['result' => 'Successfully registered', 'renewResult' => $renewResult]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
