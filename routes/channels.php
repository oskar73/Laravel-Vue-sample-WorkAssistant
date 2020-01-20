<?php

use App\Broadcasting\GuestChannel;
use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('chat', function ($user) {
//    return $user;
//});

//Broadcast::channel('guest-{session}', GuestChannel::class);

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
