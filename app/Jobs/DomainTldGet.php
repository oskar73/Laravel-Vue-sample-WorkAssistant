<?php

namespace App\Jobs;

use App\Models\DomainTld;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Namecheap\Api;
use Namecheap\Config;

class DomainTldGet implements ShouldQueue
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
            $command = Api::factory($this->config, 'domains.getTldList');
            $command->dispatch();
            if ($command->getStatus() == 'ok') {
                foreach ($command->tlds as $item) {
                    DomainTld::updateOrCreate(['Name' => $item['Name']], $item);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
