<?php

namespace Database\Seeders;

use App\Models\GraphicDesign\GraphicDesign;
use App\Models\GraphicDesign\Graphic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GraphicDesignSeeder extends Seeder
{
    const STORAGE_DISK = 's3-pub-bizinabox';
    const PREVIEW_DIRECTORY = 'favicons/previews/';
    const PREVIEW_EXTENSION = 'png';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logoCategory = GraphicCategory::where("slug", 'logo')->first()->id;
        $faviconCategory = GraphicCategory::where("slug", 'favicon')->first()->id;
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GraphicDesign::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [];
        $favicons = DB::table('favicon_items')->get();
        $logos = DB::table('logo_types')->get();
        foreach ($favicons as $favicon) {
            $data[] = [
                "hash" => $favicon->hash,
                "path" => $favicon->path,
                "status" => $favicon->status ? true : false,
                "name" => $favicon->name,
                "category_id" => $faviconCategory,
                "premium" => $favicon->premium,
                "recommend" => $favicon->recommend,
                "order" => null,
                "global_order" => null,
                "tutorial_id" => null,
                "created_at" => $favicon->created_at ?? date("Y-m-d"),
                "updated_at" => $favicon->updated_at ?? date("Y-m-d"),
                "preview" => $this->getPreviewAttribute($favicon->hash),
            ];
        }
        foreach ($logos as $logo) {
            $data[] = [
                "hash" => $logo->hash,
                "path" => $logo->path,
                "status" => $logo->enabled ? true : false,
                "name" => $logo->name,
                "category_id" => $logoCategory,
                "premium" => $logo->premium,
                "recommend" => $logo->recommend,
                "order" => null,
                "global_order" => null,
                "tutorial_id" => null,
                "created_at" => $logo->created_at ?? date("Y-m-d"),
                "updated_at" => $logo->updated_at ?? date("Y-m-d"),
                "preview" => $this->getPreviewAttribute($logo->hash),
            ];
        }

        GraphicDesign::insert($data);
    }

    public function getPreviewAttribute($hash): string
    {
        return Storage::disk(self::STORAGE_DISK)->url(self::PREVIEW_DIRECTORY.$hash.".".self::PREVIEW_EXTENSION);
    }
}
