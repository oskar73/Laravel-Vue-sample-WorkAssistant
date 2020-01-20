<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserLogin;
use App\Models\Social\Account as SocialAccount;
use App\Models\Community\Account as CommunityAccount;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Validator;

class UserManageController extends AdminController
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir . 'userManage.index');
    }

    public function edit($id)
    {
        $user = $this->model->with("roles")
            ->findorfail($id);

        return view(self::$viewDir . 'userManage.edit', compact("user"));
    }

    public function detail($id)
    {
        $user = $this->model->with("roles", "websites")
            ->withCount([
                "domains",
                "blogAdsListings",
                "readymades",
                "packages",
                "plugins",
                "services",
                "lacartes",
                "posts",
                "orders",
                "subscriptionOrderItems",
                "transactions",
                "tickets",
                "appointments",
                "purchaseFollowups",
                "logins",
                "directoryListings",
                "portfolio",
                "designs",
                "coupons",
            ])
            ->findorfail($id);

        return view(self::$viewDir . 'userManage.detail1', compact("user"));
    }

    public function getLogin()
    {
        $login = new UserLogin();

        return $login->getDatatable(request()->get("user"));
    }

    public function create()
    {
        return view(self::$viewDir . 'userManage.create');
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule());
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $this->model->storeItem($request)
                ->syncRoles($request->roles);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->profileUpdateRule($request, $id));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $user = $this->model->findorfail($id)
                ->updateProfile($request, 1)
                ->syncRoles($request->roles);

            return response()->json(['status' => 1, 'data' => $user]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'new_password' => 'required|min:8|max:191',
                'confirm_password' => 'required|same:new_password',
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $user = $this->model->findorfail($id)->updatePsw($request);

            return response()->json(['status' => 1, 'data' => $user]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function switchStatus(Request $request)
    {
        try {
            $action = $request->action;

            $items = $this->model->whereIn('id', $request->ids)->get();

            if ($action === 'active') {
                $items->each->update(['status' => 'active']);
            } elseif ($action === 'inactive') {
                $items->each->update(['status' => 'inactive']);
            } elseif ($action === 'send_verification') {
                foreach ($items as $item) {
                    $item->sendEmailVerificationNotification();
                }
            } elseif ($action === 'account_creation') {
                foreach ($items as $item) {
                    $password = uniqid();
                    $item->password = bcrypt($password);
                    $item->save();
                    $item->raw_password = $password;
                    $item->notifyNewUser();
                }
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function delete($user)
    {
        $user = User::findOrFail($user);
        if ($user->socialAccount) {
            $user->socialAccount->delete();
        }
        if ($user->communityAccount) {
            $user->communityAccount->delete();
        }
        $user->delete();

        return $this->jsonSuccess();
    }
}
