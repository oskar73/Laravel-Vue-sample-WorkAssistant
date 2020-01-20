<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogAdsPositionSeeder extends Seeder
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

        DB::table('blog_ads_positions')->truncate();

        $positions = [
            ['name' => 'Featured Area Top Right Sponsored Box', 'type' => 'home'],
            ['name' => 'Featured Area Bottom Left Sponsored Box', 'type' => 'home'],
            ['name' => 'Recent News Area Top Banner', 'type' => 'home'],
            ['name' => 'Right Top Sponsored Box', 'type' => 'home'],
            ['name' => 'All Posts Area Top Banner', 'type' => 'home'],
            ['name' => 'All Posts Top Left Sponsored Box', 'type' => 'home'],
            ['name' => 'All Posts Top Right Sponsored Box', 'type' => 'home'],
            ['name' => 'Right Middle Sponsored Box', 'type' => 'home'],
            ['name' => 'Right Bottom Sponsored Box', 'type' => 'home'],
            ['name' => 'Left Sidebar Banner', 'type' => 'home'],
            ['name' => 'Right Sidebar Banner', 'type' => 'home'],

            ['name' => 'Top Right Sponsored Box', 'type' => 'category'],
            ['name' => 'Middle Right Sponsored Box', 'type' => 'category'],
            ['name' => 'Bottom Right Sponsored Box', 'type' => 'category'],
            ['name' => 'Post Area Right Sponsored Box', 'type' => 'category'],
            ['name' => 'Post Area Left Sponsored Box', 'type' => 'category'],
            ['name' => 'Left Sidebar Banner', 'type' => 'category'],
            ['name' => 'Right Sidebar Banner', 'type' => 'category'],
            ['name' => 'Top Banner', 'type' => 'category'],

            ['name' => 'Top Right Sponsored Box', 'type' => 'detail'],
            ['name' => 'Middle Right Sponsored Box', 'type' => 'detail'],
            ['name' => 'Bottom Right Sponsored Box', 'type' => 'detail'],
            ['name' => 'Top Banner', 'type' => 'detail'],
            ['name' => 'Bottom Banner', 'type' => 'detail'],
            ['name' => 'Left Sidebar Banner', 'type' => 'detail'],
            ['name' => 'Right Sidebar Banner', 'type' => 'detail'],
            ['name' => 'Comment Area Right Sponsored Box', 'type' => 'detail'],
        ];

        foreach ($positions as $position) {
            \App\Models\BlogAdsPosition::create($position);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Model::reguard();
    }
}
