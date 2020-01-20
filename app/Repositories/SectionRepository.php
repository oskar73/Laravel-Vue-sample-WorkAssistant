<?php

namespace App\Repositories;

use App\Interfaces\SectionRepositoryInterface;
use App\Models\Builder\SectionCategory;

class SectionRepository implements SectionRepositoryInterface
{
    public function getSectionCategories()
    {
        return SectionCategory::withCount('sections')
            ->status(1)
            // ->whereNull('module')
            ->orderBy("order")
            ->with(['sections' => function ($query) {
                $query->status(1)
                    ->with(['category' => function ($query) {
                        $query->with(['sections' => function ($q) {
                            $q->status(1);
                        }]);
                    }]);
            }])
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug', 'description', 'recommended', 'sections_count']);
    }

    public function getModuleSectionCategories($websiteModules = [])
    {
        return SectionCategory::withCount('sections')
            ->status(1)
            ->whereIn('module', $websiteModules)
            ->orderBy("name")
            ->with(['sections' => function ($query) {
                $query->with(['category' => function ($query) {
                    $query->with('sections');
                }]);
            }])
            ->get(['id', 'name', 'slug', 'description', 'recommended', 'sections_count']);
    }
}
