<?php

namespace App\Console\Commands;

use App\Models\Error;
use Illuminate\Console\Command;

class DeletePublicCartSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:public-cart-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 30 min old public cart sessions';

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
        try {
            $seconds = 216000; // 30m = 216000s
            $dir = public_path('cart_sessions');
            $nofiles = 0;

            if ($handle = opendir($dir)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file == '.' || $file == '..' || is_dir($dir.'/'.$file)) {
                        continue;
                    }

                    if ((time() - filemtime($dir.'/'.$file)) > ($seconds)) {
                        $nofiles++;
                        unlink($dir.'/'.$file);
                    }
                }
                closedir($handle);
                echo "Total files deleted: $nofiles \n";
            }
        } catch (\Exception $e) {
            Error::create([
                'location' => 'App\Console\Commands\DeletePublicCartSessions::handle()',
                'error' => json_encode($e->getMessage()),
            ]);
        }
    }
}
