<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Integration\Stripe;
use App\Models\Module\EcommerceOrder;
use App\Models\Module\EcommercePayment;
use App\Models\StripeAccount;
use App\Models\AccountBalance;
use App\Models\AccountTransfer;
use App\Models\WebsiteUser;
use App\Models\EcommerceProduct;
use App\Models\UserEcommerceProduct;

class ProcessStripeWebhook implements ShouldQueue
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
        $stripeClient = new Stripe();

        switch ($this->event->type) {
            case 'checkout.session.completed':
                $session = $this->event->data->object;
                if ($session->metadata->mode == 'ecommerce') {
                    $order = EcommerceOrder::with('items.product', 'items.size', 'items.color', 'items.variant')->find($session->metadata->order);
                    $order->status = 'success';
                    $order->save();

                    $paymentIntent = $stripeClient->retrievePaymentIntent($session->payment_intent);
    
                    EcommercePayment::create([
                        'web_id'            =>  $order->web_id,
                        'order_id'          =>  $order->id,
                        'user_id'           =>  $order->user_id,
                        'customer_id'       =>  $order->customer_id,
                        'charge_id'         =>  $paymentIntent->id,
                        'payment_status'    =>  $paymentIntent->status,
                        'amount'            =>  $paymentIntent->amount / 100,
                        'method'            =>  'stripe',
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
                            'status'        =>  'success'
                        ]);
                        $item->product->quantity = $item->product->quantity - $item->quantity;
                        $item->product->save();
                    }
                }
                break;
            case 'charge.dispute.funds_withdrawn':
                $charge = $this->event->data->object;
                $paymentIntent = $stripeClient->retrievePaymentIntent($charge->payment_intent);

                $payment = EcommercePayment::where('charge_id', $charge->payment_intent)->first();
                if (!is_null($payment)) {
                    $payment->payment_status = 'disputed';
                    $payment->save();
                }
                break;
            case 'account.updated':
                $account = $this->event->data->object;

                $userId = $account->metadata->user;
                $webId = $account->metadata->web;
                $stripeAccount = StripeAccount::where('user_id', $userId)->where('web_id', $webId)->first();
                if (!is_null($stripeAccount)) {
                    $stripeAccount->charges_enabled = $account->charges_enabled;
                    $stripeAccount->payouts_enabled = $account->payouts_enabled;
                    $stripeAccount->details_submitted = $account->details_submitted;
                    $stripeAccount->save();
                }
                break;
            case 'transfer.reversed':
                $transfer = $this->event->data->object;
                break;
            case 'transfer.created':
                $transfer = $this->event->data->object;

                $transfer_id = $transfer->metadata->id;
                $transfer = AccountTransfer::find($transfer_id);
                if (!is_null($transfer)) {
                    $transfer->status = 'success';
                    $transfer->save();

                    if ($transfer->type === 'ecommerce.module') {
                        $payments = EcommercePayment::where('transfer_id', $transfer->id)->get();
                        foreach($payments as $payment) {
                            $payment->payment_status = 'withdrawn';
                            $payment->save();
                        }
                    }
                }
                break;
            default:
                echo 'Received unknown event type ' . $this->event->type;
        }
    }
}
