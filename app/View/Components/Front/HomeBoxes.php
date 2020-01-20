<?php

namespace App\View\Components\Front;

use App\Models\HomeBox;
use Illuminate\View\Component;

class HomeBoxes extends Component
{
    public $boxes = [];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $boxes = HomeBox::where('status', 1)
            ->with('media')
            ->take(20)
            ->get();

        $allBoxes = [];

        for ($i = 0; $i < 20; $i++) {
            array_push($allBoxes, $boxes[$i] ?? null);
        }

        $this->boxes = $allBoxes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.home-boxes');
    }
}
