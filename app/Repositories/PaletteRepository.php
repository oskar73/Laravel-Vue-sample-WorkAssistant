<?php

namespace App\Repositories;

use App\Interfaces\PaletteRepositoryInterface;
use App\Models\Palette;

class PaletteRepository extends BaseRepository implements PaletteRepositoryInterface
{
    /**
     * @var Palette
     */
    public $model = Palette::class;
    private PaletteCategoryRepository $categoryRepository;

    public function __construct(PaletteCategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }

    public function get()
    {
        return $this->model->orderBy('order')->orderBy('created_at')->get();
    }

    public function userSimplePalettes()
    {
        return $this->model->where('type', 'simple')
            ->where('user_id', user()->id)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
    }

    public function userAdvancedPalettes()
    {
        return $this->model->where('type', 'advanced')
            ->where('user_id', user()->id)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
    }

    public function adminSimplePalettes()
    {
        return $this->model->where('type', 'simple')
            ->whereNull('user_id')
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
    }

    public function adminAdvancedPalettes()
    {
        return $this->model->where('type', 'advanced')
            ->whereNull('user_id')
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
    }

    public function userPalettes($type)
    {
        return match ($type) {
            'simple' => $this->userSimplePalettes(),
            'advanced' => $this->userAdvancedPalettes(),
        };
    }

    public function adminPalettes($type)
    {
        return match ($type) {
            'simple' => $this->adminSimplePalettes(),
            'advanced' => $this->adminAdvancedPalettes(),
        };
    }

    public function formatPalettes($categories): array
    {
        $palettes = [];

        foreach ($categories as $category) {
            $advancedPalettes = [];

            foreach ($category->approvedPalettes as $palette) {
                $advancedPalettes[$palette->mode][] = [
                    'id' => $palette->id,
                    'name' => $palette->name,
                    'colors' => $palette->data,
                ];
            }

            $palettes[] = [
                'category_id' => $category->id, // for builder theme create new color palette category 2022.11.11
                'name' => $category->name,
                'palettes' => $advancedPalettes,
            ];
        }

        return $palettes;
    }

    public function getUserPalettes(): array
    {
        $simpleCategories = $this->categoryRepository->userSimpleCategories();
        $advancedCategories = $this->categoryRepository->userAdvancedCategories();

        return [
            'advanced' => $this->formatPalettes($advancedCategories),
            'simple' => $this->formatPalettes($simpleCategories),
        ];
    }

    public function getAdminPalettes(): array
    {
        $simpleCategories = $this->categoryRepository->adminSimpleCategories();
        $advancedCategories = $this->categoryRepository->adminAdvancedCategories();

        return [
            'advanced' => $this->formatPalettes($advancedCategories),
            'simple' => $this->formatPalettes($simpleCategories),
        ];
    }
}
