<?php

namespace App\Console\Commands;

use App\Helpers\Random;
use App\Models\Builder\Section;
use App\Models\Builder\SectionCategory;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RefreshSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:sections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old section categories and section, and remigrate updated new sections';

    /**
     * @var SectionCategory
     */
    protected SectionCategory $category;

    /**
     * @var Section
     */
    protected Section $section;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SectionCategory $category, Section $section)
    {
        parent::__construct();

        $this->category = $category;
        $this->section = $section;
    }

    private function checkImageAndAssignIfExists($data, $imageName)
    {
        if (array_key_exists("src", $data)) {
            if ($data["src"]) {
                $data["src"] = s3_asset("/builder/sample/" . $data["src"]);
            } else {
                $data["src"] = s3_asset("builder/sample/" . $imageName . ".jpg");
            }
        }

        if (array_key_exists("image", $data)) {
            if (is_array($data["image"])) {
                if ($data["image"]["src"]) {
                    $data["image"] = ["src" => s3_asset("builder/sample/" . $data["image"]["src"])];
                } else {
                    $data["image"] = ["src" => s3_asset("builder/sample/" . $imageName . ".jpg")];
                }
            } else {
                if ($data["image"]) {
                    $data["image"] = s3_asset("builder/sample/" . $data["image"]);
                } else {
                    $data["image"] = s3_asset("builder/sample/" . $imageName . ".jpg");
                }
            }
        }

        return $data;
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Truncate section_categories and sections table
//        $this->category->truncate();
//        $this->section->truncate();

        Random::seed(rand(0, 100));
        DB::transaction(function () {
            $categories = config('builder.sections');

            foreach ($categories as $category) {
                $newCategory = $this->category->updateOrCreate([
                    'name' => $category['name']
                ], [
                    'description' => $category['description'],
                    'module' => $category['module'] ?? null,
                    'order' => $category['order'] ?? 9999,
                    'recommended' => $category['recommended'] ?? 0,
                ]);

                $newCategory->limit_per_page = $newCategory->limit_per_page ?? $category['limit_per_page'] ?? 1;
                $newCategory->status = $newCategory->status ?? $category['status'] ?? 1;
                $newCategory->save();

                $data = $category["data"] ?? [];

                $seed = Random::num(0, 100);
                Random::seed($seed);

                $imageName = Random::num(1, 25);
                if (array_key_exists("elements", $data)) {
                    $elements = $data["elements"];
                    if (array_key_exists("src", $elements)) {
                        $elements["src"] = s3_asset("builder/sample/" . Random::num(1, 25) . ".jpg");
                    }

                    $elements = $this->checkImageAndAssignIfExists($elements, $imageName);

                    $data["elements"] = $elements;
                }

                foreach ($category['sections'] as $section) {
                    if (array_key_exists("items", $data)) {
                        $items = [];
                        if (array_key_exists("item", $data["items"])) {
                            $tempItem = $data["items"]["item"];
                            $seed = Random::num(0, 100);
                            Random::seed($seed);
                            for ($i = 0; $i < $data["items"]["count"]; $i++) {
                                $imageName = Random::num(1, 25);
                                $item = $this->checkImageAndAssignIfExists($tempItem, $imageName);
                                $items[] = $item;
                            }
                        } else {
                            $items = $data["items"];
                        }
                        $data["items"] = $items;
                    }

                    if (empty($section['data'])) {
                        $section['data'] = [];
                    }

                    $section['data']['data'] = $data;

                    $imageName = Random::num(1, 25);

                    if (array_key_exists("background", $section['data'])) {
                        $section['data']['background'] = $this->checkImageAndAssignIfExists($section['data']['background'], $imageName);
                    }

                    $this->section->updateOrCreate([
                        'name' => $section['name'],
                    ], [
                        'category_id' => $newCategory->id,
                        'data' => json_encode($section['data']),
                        'status' => $section['status'] ?? 1,
                    ]);
                }
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::unguard();
    }
}
