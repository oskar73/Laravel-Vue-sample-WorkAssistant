<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class PortfolioCategory extends Component
{
    public $categories;
    public $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($selected)
    {
        $this->categories = \App\Models\PortfolioCategory::with("media")
            ->orderBy("order")
            ->latest()
            ->get();

        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.portfolio-category');
    }
}
