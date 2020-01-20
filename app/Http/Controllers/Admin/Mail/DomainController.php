<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Admin\AdminController;
use App\Integration\Mailcow;
use App\Models\WebsiteMailDomain;
use App\Rules\FQDN;
use Illuminate\Http\Request;
use Validator;

class DomainController extends AdminController
{
    public function __construct(WebsiteMailDomain $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir.'mail.domain');
    }

    public function create()
    {
        return view(self::$viewDir.'mail.domainCreate');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'domain' => new FQDN(),
                'description' => 'nullable|max:600',
                'max_aliases' => 'required|integer|min:1',
                'max_mail_boxes' => 'required|integer|min:1',
                'default_mailbox_quota' => 'required|numeric',
                'max_quota_per_mailbox' => 'required|numeric|gte:default_mailbox_quota',
                'domain_total_quota' => 'required|numeric|gte:max_quota_per_mailbox',
                'rate_limit' => 'nullable|integer|min:1',
                'rate_limit_unit' => 'nullable|in:s,m,h,d',
            ]);

            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $unique = $this->model->where("domain", $request->domain)->first();

            if ($unique) {
                return response()->json(['status' => 0, 'data' => ['Domain name is already added in mail service.']]);
            }

            $params['domain'] = $request->domain;
            $params['description'] = $request->description;
            $params['aliases'] = $request->max_aliases;
            $params['mailboxes'] = $request->max_mail_boxes;
            $params['defquota'] = $request->default_mailbox_quota;
            $params['maxquota'] = $request->max_quota_per_mailbox;
            $params['quota'] = $request->domain_total_quota;
            $params['active'] = $request->status?1:0;
            $params['rl_value'] = $request->rate_limit;
            $params['rl_frame'] = $request->rate_limit_unit;

            //default parameter
            $params['backupmx'] = 0;
            $params['relay_all_recipients'] = 0;
            $params['lang'] = "en";

            $mailcow = new Mailcow();
            $resp = $mailcow->setParams($params)->addDomain();
            if ($resp[0]['type'] == 'success') {
                $this->model->storeItem($request);

                return response()->json([
                    'status' => 1,
                    'data' => 0,
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'data' => [$resp[0]['msg']],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'data' => [json_encode($e->getMessage())]]);
        }
    }
    public function edit($id)
    {
        $domain = $this->model->findorfail($id);

        return view(self::$viewDir.'mail.domainEdit', compact("domain"));
    }
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'description' => 'nullable|max:600',
                'max_aliases' => 'required|integer|min:1',
                'max_mail_boxes' => 'required|integer|min:1',
                'default_mailbox_quota' => 'required|numeric',
                'max_quota_per_mailbox' => 'required|numeric|gte:default_mailbox_quota',
                'domain_total_quota' => 'required|numeric|gte:max_quota_per_mailbox',
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $domain = $this->model->findorfail($id);

            $attr['description'] = $request->description;
            $attr['aliases'] = $request->max_aliases;
            $attr['mailboxes'] = $request->max_mail_boxes;
            $attr['defquota'] = $request->default_mailbox_quota;
            $attr['maxquota'] = $request->max_quota_per_mailbox;
            $attr['quota'] = $request->domain_total_quota;
            $attr['active'] = $request->status?1:0;

            $params['items'][] = $domain->domain;
            $params['attr'] = $attr;

            $mailcow = new Mailcow();
            $resp = $mailcow->setParams($params)->updateDomain();

            if ($resp[0]['type'] == 'success') {
                $domain->updateItem($request);

                return response()->json([
                    'status' => 1,
                    'data' => 0,
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'data' => [$resp[0]['msg']],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'data' => [json_encode($e->getMessage())]]);
        }
    }
    public function delete(Request $request)
    {
        $domain = $this->model->findorfail($request->id);

        if ($domain->accounts->count()) {
            return response()->json(['status' => 0, 'data' => ['This domain has email accounts. Please delete them first.']]);
        }

        $params[] = $domain->domain;

        $mailcow = new Mailcow();
        $resp = $mailcow->setParams($params)->deleteDomain();

        if ($resp[0]['type'] == 'success') {
            $domain->delete();

            return response()->json([
                'status' => 1,
                'data' => 0,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'data' => [$resp[0]['msg']],
            ]);
        }
    }
}
