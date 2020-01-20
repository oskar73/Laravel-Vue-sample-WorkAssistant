<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 800) as $index) {
            \App\Models\Builder\Template::create([
                'category_id' => $faker->randomDigit(),
                'header_id' => $faker->randomDigit(),
                'footer_id' => $faker->randomDigit(),
                'name' => $faker->name(),
                'slug' => $faker->slug(),
                'description' => $faker->sentence(),
                'status' => rand(0, 1) ? 1 : 0,
                'featured' => rand(0, 1) ? 1 : 0,
            ]);
        }
    }
}
