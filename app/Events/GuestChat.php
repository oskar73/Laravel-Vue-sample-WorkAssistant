<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestChat
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $guest;

    public $session;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($guest, $session)
    {
        $this->guest = $guest;
        $this->session = $session;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('guest-' . $this->session);
    }
}
