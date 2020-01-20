<?php

namespace App\Http\Livewire;

use App\Models\Social\Account as SocialAccount;
use Auth;
use Livewire\Component;

class SocialShare extends Component
{
    public $socials;

    protected $listeners = [
        'userRefresh' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.social-share');
    }
}
