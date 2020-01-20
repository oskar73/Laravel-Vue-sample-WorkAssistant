<?php

namespace App\Models;

use Yajra\DataTables\Facades\DataTables;

class UserPlugin extends BaseModel
{
    protected $table = "user_plugins";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function storeItemFromPurchase($item, $user, $orderItem)
    {
        for ($k = 0; $k < $item['quantity']; $k++) {
            $plugin = new UserPlugin();
            $plugin->user_id = $user->id;
            $plugin->order_item_id = $orderItem->id;
            $plugin->status = 'active';
            $plugin->item = $item['item'];
            $plugin->price = $item['price'];
            $plugin->save();

            if ($item['item']['meeting'] == 1 && $item['item']->meetingSet()->exists()) {
                $meeting = new UserMeeting();
                $userMeeting = $meeting->createUserMeeting($plugin, $item['item'], $user);

                Todo::storeItem($user, 'appointment', \App\Models\UserMeeting::class, $meeting->id, $orderItem->id, $userMeeting->meeting_number);
            }
            if ($item['item']['form'] == 1 && $item['item']->getForm()->exists()) {
                $form = new UserForm();
                $form->createUserForm($plugin, $item['item'], $user);

                Todo::storeItem($user, 'form', \App\Models\UserForm::class, $form->id, $orderItem->id);
            }
        }
    }
    public function meetings()
    {
        return $this->morphMany(UserMeeting::class, 'model');
    }
    public function forms()
    {
        return $this->morphMany(UserForm::class, 'model');
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, "order_item_id")->withDefault();
    }
    public function pPackage()
    {
        return $this->belongsTo(UserPackage::class, "package_pid")->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id")->withDefault();
    }
    public function getName()
    {
        return json_decode($this->item)->name ?? '';
    }
    public function getDatatable($status, $user)
    {
        $items = $this->with("orderItem", "user", "pPackage");
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
            return "<img src='".$row->user->avatar()."' title='".$row->user->name."' class='user-avatar-50'><br><a href='".route("admin.userManage.detail", $row->user->id ?? '1')."'>{$row->user->name}</a><br>({$row->user->email})";
        })->addColumn('order', function ($row) {
            if ($row->order_item_id != 0) {
                return "<a href='".route('admin.purchase.order.detail', $row->orderItem->order_id)."'>Order #{$row->orderItem->order_id}</a>";
            } elseif ($row->package_pid != 0) {
                if ($row->pPackage->package == 1) {
                    return "<a href='".route('admin.purchase.package.detail', $row->pPackage->id)."'>{$row->pPackage->getName()}</a>";
                } else {
                    return "<a href='".route('admin.purchase.readymade.detail', $row->pPackage->id)."'>{$row->pPackage->getName()}</a>";
                }
            }
        })->addColumn('itemName', function ($row) {
            return $row->getName();
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateTimeString();
        })->editColumn('status', function ($row) {
            if ($row->status == 'active') {
                return '<span class="c-badge c-badge-success">Active</span>';
            } else {
                return '<span class="c-badge c-badge-info" >'.$row->status.'</span>';
            }
        })->addColumn('action', function ($row) {
            return '<a href="'.route("admin.purchase.plugin.detail", $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-action="detail">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'user', 'order', 'status', 'action'])->make(true);
    }
}
