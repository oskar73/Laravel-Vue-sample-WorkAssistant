<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMyIP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ip {--type=http}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        if ($type === 'http') {
            $this->info('Now');
            dispatch_sync(new \App\Jobs\CheckMyIP());
        } elseif ($type === 'queue') {
            dispatch(new \App\Jobs\CheckMyIP());
        } else {
            $response = \Http::get("https://api.ipify.org?format=json");

            $this->alert("IP Check API Job IP:".$response->json()['ip']);
        }
    }
}
