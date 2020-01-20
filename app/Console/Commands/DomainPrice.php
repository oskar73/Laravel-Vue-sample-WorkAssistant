<?php

namespace App\Console\Commands;

use App\Jobs\DomainPriceGet;
use Illuminate\Console\Command;

class DomainPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:price';

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
        $this->alert('---------------------Domain Price Get started----------------------');
        dispatch(new DomainPriceGet());
        $this->alert('---------------------Domain Price Get finished----------------------');
    }
}
