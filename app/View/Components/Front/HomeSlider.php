<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class HomeSlider extends Component
{
    public $sliders = [];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sliders = \App\Models\HomeSlider::where('status', 1)
            ->with('media')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.home-slider');
    }
}
