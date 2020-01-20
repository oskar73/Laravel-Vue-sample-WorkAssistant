<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;

class FavoritePost extends Component
{
    public $isFavorite;
    public $favorite_count;
    public $post;

    public function mount($post)
    {
        $this->post = $post;
    }

    public function render()
    {
        if (\Auth::check()) {
            $this->isFavorite = user()->hasFavorited($this->post);
        } else {
            $this->isFavorite = false;
        }
        $this->favorite_count = $this->post->favoriters()->count();

        return view('livewire.favorite-post');
    }
    public function toggle()
    {
        if (\Auth::check()) {
            user()->toggleFavorite($this->post);
        } else {
            // \Session::put('url.intended', url()->previous() . "#like-area");

            \Session::put('state', $state = Str::random(80));

            $query1 = http_build_query([
                'state' => $state,
            ]);

            $query = http_build_query([
                'redirect_uri' => url()->previous().'#like-area?'.$query1,
            ]);

            return redirect(config('sso.server').'/login?'.$query);

            // return redirect()->to('/login');
        }
    }
}
