<?php

namespace App\Integration;

use Braintree\Gateway;

class Braintree
{
    public $environment;
    public $merchantId;
    public $publicKey;
    public $privateKey;
    public $gateway;

    public function __construct()
    {
        //        $this->environment = config('custom.braintree.environment');
        //        $this->merchantId = config('custom.braintree.merchantId');
        //        $this->publicKey = config('custom.braintree.publicKey');
        //        $this->privateKey = config('custom.braintree.privateKey');

        $this->gateway = new Gateway([
            'environment' => $this->environment,
            'merchantId' => $this->merchantId,
            'publicKey' => $this->publicKey,
            'privateKey' => $this->privateKey,
        ]);
    }
    public function customer()
    {
        $result = $this->gateway->customer()->create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '281.330.8004',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
        ]);

        return $result->customer;
    }
    public function clientToken($customerId = null)
    {
        if ($customerId == null) {
            return $this->gateway->clientToken()->generate();
        } else {
            return $this->gateway->clientToken()->generate([
                "customerId" => $customerId,
            ]);
        }
    }
}
