<?php

namespace App\Jobs;

use App\Models\DomainPrice;
use App\Models\DomainTld;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Namecheap\Api;
use Namecheap\Config;

class DomainPriceGet implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    public $config;

    public function __construct()
    {
        $this->config = new Config();
        $namecheap = optional(option("namecheap", []));

        $this->config->apiUser($namecheap['user'])
            ->apiKey($namecheap['key'])
            ->clientIp($namecheap['ip'])
            ->sandbox($namecheap['sandbox']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $tlds = DomainTld::select('Name')->get();
            $action = ['register', 'renew', 'reactivate', 'transfer'];
            foreach ($tlds as $row) {
                $tld = $row->Name;
                foreach ($action as $item) {
                    $data = $this->getPrice($item, $tld);
                    if ($data != 'failed') {
                        foreach ($data as $price) {
                            $priceRow = current($price->attributes());
                            $priceRow['Action'] = $item;
                            $priceRow['tld'] = $tld;

                            $this->insertPrice($priceRow);
                        }
                    } else {
                        \Log::error('Domain Tld Price getting failed.TLD:' . $tld . ', Action:' . $item);
                    }

                    sleep(5);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Domain Tld Price getting failed.'.json_encode($e->getMessage()));
        }
    }
    private function getPrice($action, $tld)
    {
        $data = [
            'ProductType' => 'DOMAIN',
            'ProductCategory' => 'DOMAINS',
            'ActionName' => $action,
            'ProductName' => $tld,
        ];
        $command = Api::factory($this->config, 'users.getPricing');
        $command->setParams($data)->dispatch();
        if ($command->getStatus() == 'ok') {
            $resp = $command->getResponse();

            return $resp->UserGetPricingResult->ProductType->ProductCategory->Product->Price ?? 'failed';
        } else {
            return 'failed';
        }
    }
    private function insertPrice($priceRow)
    {
        return DomainPrice::updateOrCreate(['tld' => $priceRow['tld'], 'Action' => $priceRow['Action'], 'Duration' => $priceRow['Duration']], $priceRow);
    }
}
