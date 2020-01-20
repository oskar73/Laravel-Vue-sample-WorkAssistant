<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class DirectoryCategory extends Component
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
        $this->categories = \App\Models\DirectoryCategory::where('parent_id',  0)
            ->with("media")
            ->withCount('approvedItems')
            ->status(1)
            ->orderBy("order")
            ->get(['id', 'name']);

        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.directory-category');
    }
}
