<?php

namespace Database\Seeders;

use App\Models\GraphicDesign\Graphic;
use Illuminate\Database\Seeder;

class GraphicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => "Logo",
                'slug' => 'logo',
                'width' => 800,
                'height' => 450,
                'status' => 1,
                'created_at' => date('Y-m-d'),
            ],
            [
                'title' => "Favicon",
                'slug' => 'favicon',
                'width' => 512,
                'height' => 512,
                'status' => 1,
                'created_at' => date('Y-m-d'),
            ],
        ];

        Graphic::insert($data);
    }
}
