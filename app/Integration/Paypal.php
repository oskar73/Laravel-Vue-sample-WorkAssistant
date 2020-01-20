<?php

namespace App\Integration;

use Srmklive\PayPal\Services\ExpressCheckout;

class Paypal
{
    private $accessToken;
    private $apiEndpoint;
    private $clientId;
    private $clientSecret;
    private $webhookId;

    public $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout;
        $options = [
            'BRANDNAME' => 'Bizinabox',
            'LOGOIMG' => 'https://bizinabox.com/assets/img/logo.png',
            'CHANNELTYPE' => 'Merchant',
        ];

        $paypal = option('paypal', null);
        $mode = optional($paypal)['sandbox'];

        if ($mode == 'live') $this->apiEndpoint = 'https://api-m.paypal.com';
        else $this->apiEndpoint = 'https://api-m.sandbox.paypal.com';

        $config['mode'] = $mode;
        $config[$mode]['username'] = optional($paypal)['username'];
        $config[$mode]['password'] = optional($paypal)['password'];
        $config[$mode]['secret'] = optional($paypal)['secret'];

        $config['payment_action'] = "Sale";
        $config['currency'] = "USD";
        $config['billing_type'] = "MerchantInitiatedBilling";
        $config['notify_url'] = route('ipn.paypal');
        $config['locale'] = "";
        $config['validate_ssl'] = true;

        $this->provider->setApiCredentials($config);
        $this->provider->addOptions($options);

        $this->clientId = optional($paypal)['client_id'];
        $this->clientSecret = optional($paypal)['client_secret'];
        $this->webhookId = optional($paypal)['webhook_id'];
    }
    public function getProvider()
    {
        return $this->provider;
    }
    public function cancelSubscription($sub_id)
    {
        $resp = $this->provider->cancelRecurringPaymentsProfile($sub_id);

        return $resp['ACK'] == 'Success'?1:0;
    }
    public function verifyWebhookSignature($request)
    {
        $auth_algo = $request->header('paypal-auth-algo') ?? $request->header('PAYPAL-AUTH-ALGO');
        $cert_url = $request->header('paypal-cert-url') ?? $request->header('PAYPAL-CERT-URL');
        $transmission_id = $request->header('paypal-transmission-id') ?? $request->header('PAYPAL-TRANSMISSION-ID');
        $transmission_sig = $request->header('paypal-transmission-sig') ?? $request->header('PAYPAL-TRANSMISSION-SIG');
        $transmission_time = $request->header('paypal-transmission-time') ?? $request->header('PAYPAL-TRANSMISSION-TIME');
        $event = $request->getContent();

        $curl = curl_init();

        $data = [
            'transmission_id' => $transmission_id,
            'transmission_time' => $transmission_time,  
            'cert_url' => $cert_url,
            'auth_algo' => $auth_algo,
            'transmission_sig' => $transmission_sig,
            'webhook_id' => $this->webhookId,
            'webhook_event' => json_decode($event, true)
        ];

        $dataJson = json_encode($data);

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiEndpoint.'/v1/notifications/verify-webhook-signature',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataJson,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->getAccessToken()
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception('webhook verification error: ' . json_encode($err));
        } else {
            $data = json_decode($response, true);
            if (optional($data)['name'] == 'VALIDATION_ERROR' || $data['verification_status'] != 'SUCCESS') {
                throw new \Exception('webhook verification failed' );
            }
        }

        return json_decode($event, true);
    }
    public function captureOrder($orderID)
    {
        $accessToken = $this->getAccessToken();
        $apiEndpoint = $this->apiEndpoint.'/v2/checkout/orders/'.$orderID.'/capture';
    
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ];

        $data = '{}';

        $ch = curl_init($apiEndpoint);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return $data;
    }
    private function getAccessToken()
    {
        // Prepare cURL options
        $ch = curl_init($this->apiEndpoint.'/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ':' . $this->clientSecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        if (isset($data['access_token'])) {
            return $data['access_token'];
        } else {
            throw new \Exception('Unable to retrieve access token: ' . json_encode($data));
        }
    }
}
