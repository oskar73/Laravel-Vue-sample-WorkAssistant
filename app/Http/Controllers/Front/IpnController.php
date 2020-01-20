<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Paypal;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;

class IpnController extends Controller
{
    public function stripe(Request $request)
    {
        \Log::info("------------webhook started!-----------------");
        \Log::info(json_encode($request->all()));
        \Log::info("------------webhook finished!----------------");
    }
    public function paypal(Request $request)
    {
        \Log::info("------------paypal webhook started!-----------------");
        \Log::info(json_encode($request->all()));
        \Log::info("------------paypal webhook finished!----------------");

        $paypal = new Paypal();
        $provider = $paypal->getProvider();

        $request->merge(['cmd' => '_notify-validate']);
        $post = $request->all();

        $response = (string) $provider->verifyIPN($post);

        if ($response === 'VERIFIED') {
            $charge_id = $request->txn_id;
            if ($charge_id) {
                $transaction = Transaction::where("charge_id", $charge_id)->first();
                if ($transaction == null) {
                    if ($request->recurring_payment_id && $request->txn_type == 'recurring_payment') {
                        $order_item = OrderItem::where("agreement_id", $request->recurring_payment_id)->where("recurrent", 1)->first();

                        if ($order_item) {
                            $order_item->storePaypalIpnPayment($request->next_payment_date, $request->txn_id, $request->recurring_payment_id, $request->amount, $order_item->user_id);
                        }
                    }
                }
            }
        }

        return true;
    }
}
