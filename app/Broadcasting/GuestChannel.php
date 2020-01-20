<?php

namespace App\Broadcasting;

class GuestChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function join($user, $session)
    {
        \Log::info($session);
        \Log::info($user);

        return $user;
    }
}
