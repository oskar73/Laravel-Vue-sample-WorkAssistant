<?php

namespace App\View\Components\Front;

use App\Models\Builder\Template;
use Illuminate\View\Component;
use Illuminate\View\View;

class HomeTemplates extends Component
{
    public array $templates = [];

    public function __construct()
    {
        $templates = Template::where('status', 1)
            ->with('media')
            ->latest()
            ->take(12)
            ->get();

        $allTemplates = [];

        for ($i = 0; $i < 12; $i++) {
            $allTemplates[] = $templates[$i] ?? null;
        }

        $this->templates = $allTemplates;
    }

    public function render(): View
    {
        return view('components.front.home-templates');
    }
}
