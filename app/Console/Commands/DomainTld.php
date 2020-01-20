<?php

namespace App\Console\Commands;

use App\Jobs\DomainTldGet;
use Illuminate\Console\Command;

class DomainTld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:tld';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('---------------------Domain Tld Get started----------------------');
        dispatch(new DomainTldGet());
        $this->alert('---------------------Domain Tld Get finished----------------------');
    }
}
