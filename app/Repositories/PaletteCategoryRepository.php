<?php

namespace App\Repositories;

use App\Interfaces\PaletteCategoryRepositoryInterface;
use App\Models\PaletteCategory;

class PaletteCategoryRepository extends BaseRepository implements PaletteCategoryRepositoryInterface
{
    public $model = PaletteCategory::class;

    public function get($type, $userId = null, $status = null)
    {
        $categories = $this->model->where('type', $type)->where('user_id', $userId);

        if ($status !== null) {
            $categories->where('status', $status ? 1 : 0);
        }

        return $categories->orderBy('order')->orderBy('created_at', 'desc')->get();
    }

    public function userSimpleCategories()
    {
        return $this->get('simple', user()->id);
    }

    public function userAdvancedCategories()
    {
        return $this->get('advanced', user()->id);
    }

    public function userCategories($type)
    {
        return match ($type) {
            'simple' => $this->userSimpleCategories(),
            'advanced' => $this->userAdvancedCategories(),
        };
    }

    public function adminSimpleCategories()
    {
        return $this->get('simple');
    }

    public function adminAdvancedCategories()
    {
        return $this->get('advanced');
    }

    public function adminCategories($type)
    {
        return match ($type) {
            'simple' => $this->adminSimpleCategories(),
            'advanced' => $this->adminAdvancedCategories(),
        };
    }

    public function userSimpleActiveCategories()
    {
        return $this->get('simple', user()->id, true);
    }

    public function userAdvancedActiveCategories()
    {
        return $this->get('advanced', user()->id, true);
    }

    public function userActiveCategories($type)
    {
        return match ($type) {
            'simple' => $this->userSimpleActiveCategories(),
            'advanced' => $this->userAdvancedActiveCategories(),
        };
    }

    public function adminSimpleActiveCategories()
    {
        return $this->get('simple', null, true);
    }

    public function adminAdvancedActiveCategories()
    {
        return $this->get('advanced', null, true);
    }

    public function adminActiveCategories($type)
    {
        return match ($type) {
            'simple' => $this->adminSimpleActiveCategories(),
            'advanced' => $this->adminAdvancedActiveCategories(),
        };
    }
}
