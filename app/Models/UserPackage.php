<?php

namespace App\Models;

use Yajra\DataTables\Facades\DataTables;

class UserPackage extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = "user_packages";

    protected $guarded = ["id", "created_at", "updated_at"];

    protected $appends = ['remain_website'];

    protected $casts = [
        'website' => 'integer',
    ];

    public function getRemainWebsiteAttribute()
    {
        if ($this->website === -1) {
            return 1;
        }

        return ($this->website - $this->current_website);
    }

    public static function boot()
    {
        parent::boot();

        static::updated(function ($obj) {
            $obj->websites->each->update(['status' => $obj->status]);
            $obj->plugins->each->update(['status' => $obj->status]);
            $obj->lacartes->each->update(['status' => $obj->status]);
            $obj->services->each->update(['status' => $obj->status]);
            $obj->meetings->each->update(['status' => $obj->status]);
        });
    }

    public function storeItemFromPurchase($item, $user, $orderItem)
    {
        for ($k = 0; $k < $item['quantity']; $k++) {
            $package = new UserPackage();
            $package->user_id = $user->id;
            $package->order_item_id = $orderItem->id;
            $package->package = $item['type'] == 'package' ? 1 : 0;

            $package->status = 'active';

            $package->item = $item['item'];
            $package->website = $item['item']['website'];
            $package->page = $item['item']['page'];
            $package->storage = $item['item']['storage'];
            $package->module = $item['item']['module'];
            $package->domain = $item['item']['domain'];
            $package->featured_module = $item['item']['featured_module'];
            $package->modules = json_encode(session("module_wishes") ?? []);
            $package->price = $item['parameter'];
            $package->save();

            if ($package->domain > 0) {
                Todo::storeItem($user, 'domain', \App\Models\UserPackage::class, $package->id, $orderItem->id);
            }
            if ($package->website > 0) {
                Todo::storeItem($user, 'website', \App\Models\UserPackage::class, $package->id, $orderItem->id, $package->website);
            }
            if ($item['item']['meeting'] == 1 && $item['item']->meetingSet()->exists()) {
                $meeting = new UserMeeting();
                $userMeeting = $meeting->createUserMeeting($package, $item['item'], $user);
                Todo::storeItem($user, 'appointment', \App\Models\UserMeeting::class, $meeting->id, $orderItem->id, $userMeeting->meeting_number);
            }
            if ($item['item']['form'] == 1 && $item['item']->getForm()->exists()) {
                $form = new UserForm();
                $form->createUserForm($package, $item['item'], $user);
                Todo::storeItem($user, 'form', \App\Models\UserForm::class, $form->id, $orderItem->id);
            }

            $package_model = $item['item'];

            foreach ($package_model->services()->where("status", 1)->get() as $service) {
                $user_service = new UserService();
                $user_service->user_id = $user->id;
                $user_service->package_pid = $package->id;
                $user_service->status = $package->status;
                $user_service->item = $service;
                $user_service->save();

                if ($service->meeting == 1 && $service->meetingSet()->exists()) {
                    $meeting = new UserMeeting();
                    $userMeeting = $meeting->createUserMeeting($user_service, $service, $user);
                    Todo::storeItem($user, 'appointment', \App\Models\UserMeeting::class, $meeting->id, $orderItem->id, $userMeeting->meeting_number);
                }
                if ($service->form == 1 && $service->getForm()->exists()) {
                    $form = new UserForm();
                    $form->createUserForm($user_service, $service, $user);
                    Todo::storeItem($user, 'form', \App\Models\UserForm::class, $form->id, $orderItem->id);
                }
            }
            foreach ($package_model->lacartes()->where("status", 1)->get() as $lacarte) {
                $user_lacarte = new UserLacarte();
                $user_lacarte->user_id = $user->id;
                $user_lacarte->package_pid = $package->id;
                $user_lacarte->status = $package->status;
                $user_lacarte->item = $lacarte;
                $user_lacarte->save();

                if ($lacarte->meeting == 1 && $lacarte->meetingSet()->exists()) {
                    $meeting = new UserMeeting();
                    $userMeeting = $meeting->createUserMeeting($user_lacarte, $lacarte, $user);
                    Todo::storeItem($user, 'appointment', \App\Models\UserMeeting::class, $meeting->id, $orderItem->id, $userMeeting->meeting_number);
                }
                if ($lacarte->form == 1 && $lacarte->getForm()->exists()) {
                    $form = new UserForm();
                    $form->createUserForm($user_lacarte, $lacarte, $user);
                    Todo::storeItem($user, 'form', \App\Models\UserForm::class, $form->id, $orderItem->id);
                }
            }
            foreach ($package_model->plugins()->where("status", 1)->get() as $plugin) {
                $user_plugin = new UserPlugin();
                $user_plugin->user_id = $user->id;
                $user_plugin->package_pid = $package->id;
                $user_plugin->status = $package->status;
                $user_plugin->item = $plugin;
                $user_plugin->save();

                if ($plugin->meeting == 1 && $plugin->meetingSet()->exists()) {
                    $meeting = new UserMeeting();
                    $userMeeting = $meeting->createUserMeeting($user_plugin, $plugin, $user);
                    Todo::storeItem($user, 'appointment', \App\Models\UserMeeting::class, $meeting->id, $orderItem->id, $userMeeting->meeting_number);
                }
                if ($plugin->form == 1 && $plugin->getForm()->exists()) {
                    $form = new UserForm();
                    $form->createUserForm($user_plugin, $plugin, $user);
                    Todo::storeItem($user, 'form', \App\Models\UserForm::class, $form->id, $orderItem->id);
                }
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id")->withDefault();
    }

    public function meetings()
    {
        return $this->morphMany(UserMeeting::class, 'model');
    }

    public function forms()
    {
        return $this->morphMany(UserForm::class, 'model');
    }

    public function getName()
    {
        return json_decode($this->item)->name ?? '';
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, "order_item_id")->withDefault();
    }

    public function getOriginalPackage()
    {
        return Package::with('modules')->find(json_decode($this->item)->id);
    }

    public function websites()
    {
        return $this->hasMany(Website::class, "package_id");
    }

    public function progresses()
    {
        return $this->hasMany(PackageWebsiteProgress::class, "package_id");
    }

    public function domains()
    {
        return $this->hasMany(Domain::class, "package_id");
    }

    public function plugins()
    {
        return $this->hasMany(UserPlugin::class, 'package_pid');
    }

    public function lacartes()
    {
        return $this->hasMany(UserLacarte::class, 'package_pid');
    }

    public function services()
    {
        return $this->hasMany(UserService::class, 'package_pid');
    }

    public function getDatatable($status, $user, $type)
    {
        if ($type == 'package') {
            $items = $this->with("orderItem", "user")->where("package", 1);
        } else {
            $items = $this->with("orderItem", "user")->where("package", 0);
        }
        if ($status == 'all') {
            $result = $items;
        } elseif ($status == 'active') {
            $result = $items->where("status", "active");
        } else {
            $result = $items->where("status", "!=", "active");
        }
        if ($user != 'all') {
            $result = $result->where("user_id", $user);
        }

        return Datatables::of($result)->addColumn('user', function ($row) {
            return "<img src='" . $row->user->avatar() . "' title='" . $row->user->name . "' class='user-avatar-50'><br><a href='" . route("admin.userManage.detail", $row->user->id ?? '1') . "'>{$row->user->name}</a><br>({$row->user->email})";
        })->addColumn('order', function ($row) {
            return "<a href='" . route('admin.purchase.order.detail', $row->orderItem->order_id) . "'>Order #{$row->orderItem->order_id}</a>";;
        })->addColumn('itemName', function ($row) {
            return $row->orderItem->getName();
        })->addColumn('payment', function ($row) {
            return $row->orderItem->recurrent == 1 ? 'Recurrent' : 'Onetime';
        })->addColumn('due_date', function ($row) {
            return $row->orderItem->due_date;
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->toDateTimeString();
        })->editColumn('status', function ($row) {
            if ($row->status == 'active') {
                return '<span class="c-badge c-badge-success">Active</span>';
            } else {
                return '<span class="c-badge c-badge-info" >' . $row->status . '</span>';
            }
        })->addColumn('action', function ($row) use ($type) {
            return '<a href="' . route("admin.purchase." . $type . ".detail", $row->id) . '" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'user', 'order', 'status', 'action'])->make(true);
    }
}
