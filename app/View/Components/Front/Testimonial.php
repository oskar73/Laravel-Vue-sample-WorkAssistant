<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class Testimonial extends Component
{
    public $items;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->items = \App\Models\Testimonial::with('media')
            ->whereStatus(1)
            ->orderBy("order")
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.front.testimonial');
    }
}
