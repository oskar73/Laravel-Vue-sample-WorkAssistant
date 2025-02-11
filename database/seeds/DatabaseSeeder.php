<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DomainPriceSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(LegalPageSeeder::class);
        $this->call(BlogAdsPositionSeeder::class);
        $this->call(GraphicCategorySeeder::class);
    }
}
