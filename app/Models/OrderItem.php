<?php

namespace App\Models;

use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class OrderItem extends BaseModel
{
    protected $table = "order_items";

    protected $guarded = ["id", "created_at", "updated_at"];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($obj) {
            $obj->nameToUserProduct($obj->product_type)::where("order_item_id", $obj->id)->get()->each->update(['status' => $obj->status]);
        });
    }
    public function storePaypalIpnPayment($due_date, $transaction_id, $agreement_id, $amount, $user_id)
    {
        $order_item = $this;
        $order_item->status = "active";
        $order_item->due_date = $due_date;
        $order_item->save();

        $transaction = new Transaction();
        $transaction->storeSubscriptionCharge($transaction_id, $agreement_id, $amount, $user_id, 'paypal')
            ->makeInvoice();

        return $order_item;
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id")->withDefault();
    }
    public function order()
    {
        return $this->belongsTo(Order::class, "order_id")->withDefault();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, "agreement_id", "agreement_id")->latest();
    }
    public function updateSubscriptionStatus($data)
    {
        $item = $this->where("recurrent", 1)
            ->where("agreement_id", $data['subscription'])
            ->first();

        $item->status = $data['status'] == 'paid'? 'active':$data['status'];
        $item->due_date = Carbon::createFromTimestamp($data['lines']['data'][0]['period']['end'])->toDateTimeString();
        $item->save();

        return $item;
    }
    public function getName()
    {
        $detail = json_decode($this->product_detail);

        return $detail->name ?? '';
    }
    public function getItem()
    {
        return $this->hasOne($this->nameToUserProduct($this->product_type), 'order_item_id')->withDefault();
    }
    public function getRecurrentPrice()
    {
        $price = json_decode($this->price);

        $result = formatNumber($price->price) . "/" . $price->period ." " . \Str::plural($price->period_unit, $price->period);

        return $result;
    }

    public function getSubscriptionDatatable($status, $user)
    {
        switch ($status) {
            case 'all':
                $subscriptions = $this::with('user', 'order')->where("recurrent", 1);

                break;
            case 'active':
                $subscriptions = $this::with('user', 'order')->where("recurrent", 1)->where("status", "active");

                break;
            case 'inactive':
                $subscriptions = $this::with('user', 'order')->where("recurrent", 1)->where("status", "!=", "active");

                break;
        }
        if ($user != 'all') {
            $subscriptions = $subscriptions->where("user_id", $user);
        }

        return Datatables::of($subscriptions)->addColumn('user', function ($row) {
            return "<img src='".$row->user->avatar()."' title='".$row->user->name."' class='user-avatar-50'><br><a href='".route("admin.userManage.detail", $row->user->id ?? '1')."'>{$row->user->name}</a><br>({$row->user->email})";
        })->editColumn('order_id', function ($row) {
            return "<a href='".route('admin.purchase.order.detail', $row->order_id)."'>#{$row->order_id}</a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateTimeString();
        })->editColumn('product_type', function ($row) {
            return moduleName($row->product_type);
        })->addColumn('product_name', function ($row) {
            return $row->getName();
        })->addColumn('price_detail', function ($row) {
            return "$" .$row->getRecurrentPrice();
        })->editColumn('status', function ($row) {
            if ($row->status === 'active') {
                $result = "<span class='c-badge c-badge-success'>".$row->status."</span>";
            } else {
                $result = "<span class='c-badge c-badge-danger'>".ucfirst($row->status)."</span>";
            }

            return $result;
        })->addColumn('action', function ($row) {
            return '<a href="'.route('admin.purchase.subscription.detail', $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-action="detail">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'user', 'order_id', 'status', 'action'])->make(true);
    }
    public function getUserSubscriptionDatatable($status)
    {
        switch ($status) {
            case 'all':
                $subscriptions = $this::with('order')
                    ->where("recurrent", 1)
                    ->where("user_id", user()->id);

                break;
            case 'active':
                $subscriptions = $this::with('order')
                    ->where("recurrent", 1)
                    ->where("status", "active")
                    ->where("user_id", user()->id);

                break;
            case 'inactive':
                $subscriptions = $this::with('order')
                    ->where("recurrent", 1)
                    ->where("status", "!=", "active")
                    ->where("user_id", user()->id);

                break;
        }

        return Datatables::of($subscriptions)->editColumn('order_id', function ($row) {
            return "<a href='".route('user.purchase.order.detail', $row->order_id)."'>#{$row->order_id}</a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateTimeString();
        })->editColumn('product_type', function ($row) {
            return moduleName($row->product_type);
        })->addColumn('product_name', function ($row) {
            return $row->getName();
        })->addColumn('price_detail', function ($row) {
            return "$" .$row->getRecurrentPrice();
        })->editColumn('status', function ($row) {
            if ($row->status === 'active') {
                $result = "<span class='c-badge c-badge-success'>".$row->status."</span>";
            } else {
                $result = "<span class='c-badge c-badge-danger'>".ucfirst($row->status)."</span>";
            }

            return $result;
        })->addColumn('action', function ($row) {
            return '<a href="'.route('user.purchase.subscription.detail', $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-action="detail">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'order_id', 'status', 'action'])->make(true);
    }
}
