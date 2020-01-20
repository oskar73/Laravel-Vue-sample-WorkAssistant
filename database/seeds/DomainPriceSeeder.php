<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('domain_prices')->truncate();
        $path = __DIR__.'/source/domain_prices.sql';
        DB::unprepared(file_get_contents($path));

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
