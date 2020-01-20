<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Jobs\ProcessStripeWebhook;
use App\Jobs\ProcessPaypalWebhook;

use App\Integration\Stripe;
use App\Integration\Paypal;

class WebhookController extends Controller
{
    public function stripeAccount(Request $request)
    {
        $stripeClient = new Stripe();
        $webhook_secret = $stripeClient->stripe_webhook_account_secret;
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $stripeClient->stripe_webhook_account_secret
            );
            ProcessStripeWebhook::dispatch($event);
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        http_response_code(200);
    }

    public function stripeConnect(Request $request)
    {
        $stripeClient = new Stripe();
        $webhook_secret = $stripeClient->stripe_webhook_connect_secret;
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $webhook_secret
            );
            ProcessStripeWebhook::dispatch($event);
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        http_response_code(200);
    }

    public function paypalWebhook(Request $request) {
        $paypal = new Paypal();

        try {
            $event = $paypal->verifyWebhookSignature($request);
            ProcessPaypalWebhook::dispatch($event);
        } catch(\Exception $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        http_response_code(200);
    }
}
