<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegalPageSeeder extends Seeder
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
        DB::table('legal_pages')->truncate();

        $slugs = [
            "payment-policy",
            "privacy-policy",
            "terms-and-conditions",
            "disclaimer",
            "cookie-content",
            "about",
        ];
        foreach ($slugs as $slug) {
            \App\Models\LegalPage::create([
                'slug' => $slug,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
