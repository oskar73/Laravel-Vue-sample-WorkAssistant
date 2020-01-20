<?php

namespace App\Integration;

class Cpanel
{
    private $host;
    public $username;
    private $authType;
    private $password;
    public $cpanel;
    public $account;
    public $subdomain;
    public $fullsubdomain;

    public function __construct()
    {
        //        $this->host = config('custom.bizinasite.host');
        //        $this->username = config('custom.bizinasite.username');
        //        $this->password = config('custom.bizinasite.password');
        //        $this->authType = config('custom.bizinasite.authType');
        //        $this->account = config('custom.bizinasite.account');
        //        $this->subdomain = config('custom.bizinasite.subdomain');
        //        $this->fullsubdomain = config('custom.bizinasite.fullsubdomain');

        $this->cpanel = new \Gufy\CpanelPhp\Cpanel([
            'host' => $this->host, // ip or domain complete with its protocol and port
            'username' => $this->username, // username of your server, it usually root.
            'auth_type' => $this->authType, // set 'hash' or 'password'
            'password' => $this->password, // long hash or your user's password
        ]);
        $this->cpanel->setTimeout(60);
        $this->cpanel->setConnectionTimeout(10);
    }
    public function park($domain)
    {
        $status = 0;
        $message = '';

        try {
            $array = [
                'domain' => $domain,
                'topdomain' => $this->subdomain,
            ];
            $res = $this->cpanel->execute_action(2, 'Park', 'park', $this->account, $array);

            $response = json_encode($res);
            $result = json_decode($response);
            if ($response && $result && isset($result->cpanelresult) && isset($result->cpanelresult->data) && isset($result->cpanelresult->data[0]) && isset($result->cpanelresult->data[0]->result) && isset($result->cpanelresult->data[0]->reason)) {
                $status = $result->cpanelresult->data[0]->result;
                $message = $result->cpanelresult->data[0]->reason;
            }
            if ($message == '') {
                $message = 'Domain added successfully!';
            }

            return ['status' => $status, 'message' => $message];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    public function unpark($domain)
    {
        $status = 0;
        $message = '';

        try {
            $res = $this->cpanel->execute_action(2, 'Park', 'unpark', $this->account, ['domain' => $domain, 'subdomain' => $this->fullsubdomain]);
            $response = json_encode($res);
            $result = json_decode($response);
            if ($response && $result && isset($result->cpanelresult) && isset($result->cpanelresult->data) && isset($result->cpanelresult->data[0]) && isset($result->cpanelresult->data[0]->result) && isset($result->cpanelresult->data[0]->reason)) {
                $status = $result->cpanelresult->data[0]->result;
                $message = $result->cpanelresult->data[0]->reason;
            }
            if ($message == '') {
                $message = 'Domain removed from list.!';
            }

            return ['status' => $status, 'message' => $message];
        } catch (\Exception $e) {
            return ['status' => 0, 'message' => $e->getMessage()];
        }
    }
    public function dnsLookup($domain)
    {
        $status = 0;
        $message = '';

        try {
            $res = $this->cpanel->execute_action(2, 'Park', 'unpark', $this->account, ['domain' => $domain, 'subdomain' => $this->fullsubdomain]);
        } catch (\Exception $e) {
            return ['status' => 0, 'message' => $e->getMessage()];
        }
    }
}
