<?php

namespace App\Models;

use Yajra\DataTables\Facades\DataTables;

class UserBlogPackage extends BaseModel
{
    protected $table = "user_blog_packages";

    protected $guarded = ["id", "created_at", "updated_at"];
    protected $appends = ['remain_post'];

    public function getRemainPostAttribute()
    {
        if ($this->post_number === -1) {
            return 1;
        }

        return ($this->post_number - $this->current_number);
    }
    public function storeItemFromPurchase($item, $user, $orderItem)
    {
        for ($k = 0; $k < $item['quantity']; $k++) {
            $blogPackage = new UserBlogPackage();
            $blogPackage->user_id = $user->id;
            $blogPackage->order_item_id = $orderItem->id;
            $blogPackage->post_number = $item['item']['post_number'];
            if ($orderItem->recurrent == 0) {
                $blogPackage->status = 'active';
            } else {
                $blogPackage->status = 'pending';
            }
            $blogPackage->item = $item['item'];
            $blogPackage->price = $item['parameter'];
            $blogPackage->save();

            Todo::storeItem($user, 'blog', \App\Models\UserBlogPackage::class, $blogPackage->id, $orderItem->id);

            if ($item['item']['meeting'] == 1 && $item['item']->meetingSet()->exists()) {
                $meeting = new UserMeeting();
                $userMeeting = $meeting->createUserMeeting($blogPackage, $item['item'], $user);

                Todo::storeItem($user, 'appointment', \App\Models\UserMeeting::class, $meeting->id, $orderItem->id, $userMeeting->meeting_number);
            }
            if ($item['item']['form'] == 1 && $item['item']->getForm()->exists()) {
                $form = new UserForm();
                $form->createUserForm($blogPackage, $item['item'], $user);

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
    public function user()
    {
        return $this->belongsTo(User::class, "user_id")->withDefault();
    }
    public function getName()
    {
        return json_decode($this->item)->name ?? '';
    }
    public function getDatatable($status)
    {
        $items = $this->with("orderItem", "user");
        if ($status == 'all') {
            $result = $items;
        } elseif ($status == 'active') {
            $result = $items->where("status", "active");
        } else {
            $result = $items->where("status", "!=", "active");
        }

        return Datatables::of($result)->addColumn('user', function ($row) {
            return "<img src='".$row->user->avatar()."' title='".$row->user->name."' class='user-avatar-50'><br><a href='".route("admin.userManage.detail", $row->user->id ?? '1')."'>{$row->user->name}</a><br>({$row->user->email})";
        })->addColumn('order', function ($row) {
            return "<a href='".route('admin.purchase.order.detail', $row->orderItem->order_id)."'>Order #{$row->orderItem->order_id}</a>";
        })->addColumn('itemName', function ($row) {
            return $row->orderItem->getName();
        })->addColumn('payment', function ($row) {
            return $row->orderItem->recurrent == 1?'Recurrent':'Onetime';
        })->addColumn('due_date', function ($row) {
            return $row->orderItem->due_date;
        })->editColumn('post_number', function ($row) {
            $post_number = $row->post_number == -1?'Unlimited':$row->post_number;

            return $post_number . " / " . $row->current_number;
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateTimeString();
        })->editColumn('status', function ($row) {
            if ($row->status == 'active') {
                return '<span class="c-badge c-badge-success">Active</span>';
            } else {
                return '<span class="c-badge c-badge-info" >'.$row->status.'</span>';
            }
        })->addColumn('action', function ($row) {
            return '<a href="'.route("admin.purchase.blog.detail", $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-action="detail">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'user', 'order', 'status', 'action'])->make(true);
    }
}
