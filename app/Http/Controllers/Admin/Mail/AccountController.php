<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Admin\AdminController;
use App\Integration\Mailcow;
use App\Models\WebsiteMailAccount;
use App\Models\WebsiteMailDomain;
use Illuminate\Http\Request;
use Validator;

class AccountController extends AdminController
{
    public function __construct(WebsiteMailAccount $model)
    {
        $this->model = $model;
    }

    public function index($id)
    {
        if ($id != 'all') {
            $domain = WebsiteMailDomain::whereId($id)->where("status", 1)->firstorfail();
        }

        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"), $id);
        }

        return view(self::$viewDir.'mail.account', compact("domain"));
    }
    public function create($id)
    {
        $domain = WebsiteMailDomain::findorfail($id);
        if (! $domain->canCreateAccount()) {
            return back()->with("error", "Sorry, this domain isn\'t allowed to create account. Please check the status of domain or max accounts limit.");
        }

        return view(self::$viewDir.'mail.accountCreate', compact("domain"));
    }
    public function store(Request $request, $id)
    {
        $domain = WebsiteMailDomain::findorfail($id);

        if (! $domain->canCreateAccount()) {
            return response()->json(['status' => 0, 'data' => ["Sorry, this domain isn\'t allowed to create account. Please check the status of domain or max accounts limit."]]);
        }

        $validation = Validator::make($request->all(), [
            'username' => 'required|string|max:45|alpha_dash',
            'name' => 'required|string|max:45',
            'quota' => 'required|integer|max:'.$domain->max_quota_per_mailbox,
            'password' => 'required|min:8|max:191|case_diff|numbers|letters|symbols',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => 0, 'data' => $validation->errors()]);
        }

        if (($domain->totalQuota() + $request->quota) > $domain->total_quota) {
            return response()->json(['status' => 0, 'data' => ["You are exceeding total quota of this domain."]]);
        }

        $mailcow = new Mailcow();
        $params['local_part'] = $request->username;
        $params['domain'] = $domain->domain;
        $params['name'] = $request->name;
        $params['quota'] = $request->quota;
        $params['password'] = $request->password;
        $params['password2'] = $request->password_confirmation;
        $params['active'] = $request->status?1:0;
        $params['force_pw_update'] = $request->force_password_update?1:0;
        $params['tls_enforce_in'] = 1;
        $params['tls_enforce_out'] = 1;

        $resp = $mailcow->setParams($params)->createMailbox();
        if ($resp[0]['type'] == 'success') {
            $this->model->storeItem($request, $domain);

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
    public function edit($id)
    {
        $account = $this->model->with('domain')->findorfail($id);
        if ($account->domain->status == 0) {
            return back()->with("error", 'Account domain status is inactive now. You can\'t edit accounts under inactive domain.');
        }

        return view(self::$viewDir.'mail.accountEdit', compact("account"));
    }
    public function update(Request $request, $id)
    {
        $account = $this->model->with('domain')->findorfail($id);
        if ($account->domain->status == 0) {
            return response()->json(['status' => 0, 'data' => ['Account domain status is inactive now. You can\'t edit accounts under inactive domain.']]);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:45',
            'quota' => 'required|integer|max:'.$account->domain->max_quota_per_mailbox,
            'password' => 'nullable|min:8|max:191|case_diff|numbers|letters|symbols',
            'password_confirmation' => 'nullable|same:password',
        ]);
        if ($validation->fails()) {
            return response()->json(['status' => 0, 'data' => $validation->errors()]);
        }

        if (($account->domain->totalQuota() + $request->quota - $account->quota) > $account->domain->total_quota) {
            return response()->json(['status' => 0, 'data' => ["You are exceeding total quota of this domain."]]);
        }

        $attr['name'] = $request->name;
        $attr['quota'] = $request->quota;
        $attr['password'] = $request->password;
        $attr['password2'] = $request->password_confirmation;
        $attr['active'] = $request->status?1:0;
        $attr['force_pw_update'] = $request->force_password_update;
        $attr['sogo_access'] = 1;

        $params['items'][] = $account->email;
        $params['attr'] = $attr;

        $mailcow = new Mailcow();
        $resp = $mailcow->setParams($params)->updateMailbox();

        if ($resp[0]['type'] == 'success') {
            $account->updateItem($request);

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
    public function delete(Request $request)
    {
        $account = $this->model->findorfail($request->id);

        $params[] = $account->email;

        $mailcow = new Mailcow();
        $resp = $mailcow->setParams($params)->deleteMailbox();

        if ($resp[0]['type'] == 'success') {
            $account->delete();

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
