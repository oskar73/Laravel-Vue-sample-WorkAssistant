<?php

namespace App\Models;

class Invoice extends BaseModel
{
    protected $table = "invoices";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id")->withDefault();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, "transaction_id")->withDefault();
    }

    public function sendNotification()
    {
        $user = User::find($this->user_id);
        $admin = User::find(1);

        $notification = new NotificationTemplate();
        $data1['username'] = $user->name;
        $data1['url'] = route('user.purchase.transaction.invoice', $this->id);
        $notification->sendNotification($data1, $notification::INVOICE_USER, $user);

        $data2['username'] = $admin->name;
        $data2['user'] = $user->name;
        $data2['url'] = route('admin.purchase.transaction.invoice', $this->id);
        $notification->sendNotification($data2, $notification::INVOICE_ADMIN, $admin);
    }
}
