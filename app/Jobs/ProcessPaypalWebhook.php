<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Module\EcommerceCustomer;
use App\Models\Module\EcommerceOrder;
use App\Models\Module\EcommercePayment;
use App\Models\PaypalAccount;
use App\Models\UserEcommerceProduct;

use App\Integration\Paypal;

class ProcessPaypalWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $resource = $this->event['resource'];

        switch($this->event['event_type']) {
            case 'CHECKOUT.ORDER.COMPLETED':
                $units = $resource['purchase_units'];
                foreach ($units as $unit) {
                    $captures = $unit['payments']['captures'];
                    foreach ($captures as $key => $capture) {
                        $status = 'success';
                        if ($capture['status'] !== 'COMPLETED') {
                            $status = 'failed';
                        }
                        
                        $orderId = $capture['custom_id'];
                        $order = EcommerceOrder::with('items.product', 'items.size', 'items.color', 'items.variant')->find($orderId);
                        if (!is_null($order)) {
                            $order->status = $status;
                            $order->save();
        
                            $account_id = $resource['payment_source']['paypal']['account_id'];
                            $account = EcommerceCustomer::find($order->customer_id);
                            $account->payment_account_id = $account_id;
                            $account->save();
        
                            EcommercePayment::create([
                                'web_id'            =>  $order->web_id,
                                'order_id'          =>  $order->id,
                                'user_id'           =>  $order->user_id,
                                'customer_id'       =>  $order->customer_id,
                                'charge_id'         =>  $capture['id'],
                                'payment_status'    =>  $status,
                                'amount'            =>  (float) $capture['amount']['value'],
                                'method'            =>  'paypal',
                                'fees'              =>  0
                            ]);
        
                            foreach($order->items as $item)
                            {
                                UserEcommerceProduct::create([
                                    'web_id'        =>  $order->web_id,
                                    'user_id'       =>  $order->user_id,
                                    'order_item_id' =>  $item->id,
                                    'price'         =>  $item->total,
                                    'quantity'      =>  $item->quantity,
                                    'size'          =>  $item->size->name ?? null,
                                    'color'         =>  $item->color->name ?? null,
                                    'custom'        =>  $item->variant->name ?? null,
                                    'status'        =>  $status
                                ]);
                                if ($status == 'success') {
                                    $item->product->quantity = $item->product->quantity - $item->quantity;
                                    $item->product->save();
                                }
                            }
                        }
                    }
                }
                break;
            case 'CHECKOUT.ORDER.APPROVED':
                $paypal = new Paypal();
                $paypal->captureOrder($resource['id']);
                break;
            case 'MERCHANT.ONBOARDING.COMPLETED':
                $merchant_id = $resource['merchant_id'];
                $tracking_id = $resource['tracking_id'];

                $paypalAccount = PaypalAccount::where('merchant_id', $tracking_id)->where('merchant_paypal_id', $merchant_id)->first();
                if (!is_null($paypalAccount)) {
                    $paypalAccount->payments_receivable = true;
                    $paypalAccount->primary_email_confirmed = true;
                    $paypalAccount->details_submitted = true;
                    $paypalAccount->permission_granted = true;
                    $paypalAccount->save();
                }
                break;
            case 'MERCHANT.PARTNER-CONSENT.REVOKED':
                $merchant_id = $resource['merchant_id'];
                break;
            default:
                echo 'Received unknown event type ' . $this->event['event_type'];
        }
    }
}
