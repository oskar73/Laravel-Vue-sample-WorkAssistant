<?php

namespace App\Console\Commands;

use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicCategory;
use App\Models\GraphicDesign\GraphicDesign;
use App\Models\GraphicDesignCategory;
use App\Models\Logo\LogoCategory;
use App\Models\Logo\LogoType;
use App\View\Components\Front\LogoTypeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:local {--fn=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fn = $this->option('fn');

        $this->$fn();
    }

    private function copyLogoTypesToDesignsTable()
    {
        $logotypes = LogoType::all();
        $logoGraphic = Graphic::findBySlug('logo');

        $logoCategories = LogoCategory::all();

//        foreach ($logoCategories as $category) {
//            GraphicCategory::updateOrCreate([
//                'id' => $category->id,
//                'graphic_id' => $logoGraphic->id,
//                'name' => $category->name,
//                'description' => $category->description,
//                'order' => $category->order,
//            ]);
//        }

        $logoTypesCategories = DB::table('logo_category_types')
            ->leftJoin('logo_types', 'logo_category_types.logotype_id', 'logo_types.id')
            ->leftJoin('graphic_designs', 'graphic_designs.hash', 'logo_types.hash')
            ->leftJoin('logo_categories', 'logo_category_types.category_id', 'logo_categories.id')
            ->leftJoin('graphic_categories', 'graphic_categories.name', 'logo_categories.name')
            ->select(['graphic_designs.id as design_id', 'graphic_categories.id as category_id', 'logo_category_types.order'])
            ->get();

        foreach ($logoTypesCategories as $logoTypesCategory) {
            try {
                GraphicDesignCategory::updateOrCreate([
                    'design_id' => $logoTypesCategory->design_id,
                    'category_id' => $logoTypesCategory->category_id,
                    'order' => $logoTypesCategory->order,
                ]);
            } catch (\Exception $exception) {
                $this->info($logoTypesCategory->logotype_id);
                continue;
            }
        }


//        foreach ($logotypes as $logotype) {
//            GraphicDesign::updateOrCreate([
//                'hash' => $logotype->hash,
//                'path' => $logotype->path,
//                'name' => $logotype->name,
//                'status' => $logotype->status ? 1 : 0,
//                'premium' => $logotype->premium ? 1 : 0,
//                'recommend' => $logotype->recommend ? 1 : 0,
//                'order' => $logotype->order,
//                'global_order' => $logotype->global_order,
//                'tutorial_id' => $logotype->tutorial_id,
//                'graphic_id' => $logoGraphic->id,
//                'preview' => $logotype->preview,
//            ]);
//        }
    }
}
