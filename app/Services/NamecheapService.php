<?php

namespace App\Services;

use Namecheap\Api;
use Namecheap\Config;
use Illuminate\Support\Facades\Http;

class NamecheapService
{
    public $command;

    private function getConfig()
    {
        $config = new Config();

        $namecheap = optional(option("namecheap", []));

        $config->apiUser($namecheap['user'])
            ->apiKey($namecheap['key'])
            ->clientIp($namecheap['ip'])
            ->sandbox($namecheap['sandbox']);

        return $config;
    }

    private function run()
    {
        if ($this->command) {
            if ($this->command->status() == 'ok') {
                return $this->command->getResponse();
            }

            return $this->command->status();
        }

        return 'Command is undefined';
    }

    public function addRecord($domainName, $ip)
    {
        $data['SLD'] = getDomainName($domainName);
        $data['TLD'] = getDomainTld($domainName);
        $data['HostName1'] = '@';
        $data['RecordType1'] = 'A';
        $data['Address1'] = $ip;
        $data['TTL1'] = 60;
        $data['TTL'] = 60;

        $proxyUrl = '/namecheap/domains/dns/set-hosts';
        $result = $this->getProxyResponse($proxyUrl, $data);
        return $result;


        $this->command = Api::factory($this->getConfig(), 'domains.dns.setHosts');
        $this->command->setParams($data)->dispatch();

        return $this->run();
    }

    public function getHost($domainName)
    {
        $data = [
            'SLD' => getDomainName($domainName),
            'TLD' => getDomainTld($domainName),
        ];
        $proxyUrl = '/namecheap/domains/dns/get-hosts';

        return $this->getProxyResponse($proxyUrl, $data);

        $this->command = Api::factory($this->getConfig(), 'domains.dns.getHosts');
        $this->command->setParams($data)->dispatch();

        return $this->run();
    }

    public function check($domainName)
    {
        $proxyUrl = '/namecheap/domains/check';
        return $this->getProxyResponse($proxyUrl, [$domainName]);

        $this->command = Api::factory($this->getConfig(), 'domains.check');
        $this->command->domainList([$domainName])->dispatch();

        return $this->run();
    }

    public function getList()
    {
        $data = [
            'ListType' => 'ALL',
            'SearchTerm' => '',
        ];
        $proxyUrl = '/namecheap/domains/get-list';
        return $this->getProxyResponse($proxyUrl, $data);

        $this->command = Api::factory($this->getConfig(), 'domains.getList');
        $this->command->setParams($data)->dispatch();

        return $this->run();
    }

    public function getProxyResponse($url, $data) {
        try {
            $server = config('proxy.server').'/api';
            $token = config('proxy.token');
            $namecheapConfig = optional(option("namecheap", []));

            $payload = [
                'data'      =>  $data,
                'config'    =>  [
                    'apiUser' => $namecheapConfig['user'],
                    'apiKey' => $namecheapConfig['key'],
                    'clientIp' => $namecheapConfig['ip'],
                    'sandbox' => $namecheapConfig['sandbox'],
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post($server.$url, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['status'] == 0) {
                    return [
                        'status' => 0,
                        'result' => $responseData['result']
                    ];
                } else {
                    $respData = [];
                    foreach ($responseData['result'] as $key => $value) {
                        if ($key == '@attributes') {
                            // Assign the first array to $formatedRes[0]
                            $respData[0] = $value;
                        } else {
                            // Assign other arrays to their respective keys
                            $respData[$key] = $value;
                        }
                    }

                    return [
                        'status' => 1,
                        'result' => $respData,
                        'command' => $responseData['command'] ?? null
                    ];
                }
            } else {
                return [
                    'status' => 0,
                    'result' => ['Connection error! Sorry for inconvenience.']
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 0,
                'result' => [$e->getMessage()],
            ];
        }
    }
}
