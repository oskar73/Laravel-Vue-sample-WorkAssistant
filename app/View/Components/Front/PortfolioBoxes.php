<?php

namespace App\View\Components\Front;

use App\Models\Portfolio;
use Illuminate\View\Component;

class PortfolioBoxes extends Component
{
    public $boxes1 = [];
    public $boxes2 = [];
    public $boxes3 = [];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $boxes = Portfolio::whereNotNull('approved_at')
            ->orderBy('created_by', 'asc')
            ->orderBy('approved_at', 'desc')
            ->where('status', 1)
            ->take('17')
            ->get();

        $allBoxes = [];

        for ($i = 0; $i < 17; $i++) {
            if ($i < 8) {
                $index = $i;
            } else {
                $index = $i - 1;
            }

            if ($i === 8) {
                array_push($allBoxes, 'home');
            } else {
                array_push($allBoxes, $boxes[$index] ?? null);
            }
        }

        $this->boxes1 = array_slice($allBoxes, 0, 6);
        $this->boxes2 = array_slice($allBoxes, 6, 5);
        $this->boxes3 = array_slice($allBoxes, 11, 6);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.portfolio-boxes');
    }
}
