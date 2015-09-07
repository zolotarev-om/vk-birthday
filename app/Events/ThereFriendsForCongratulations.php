<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ThereFriendsForCongratulations extends Event
{
    use SerializesModels;

    /**
     * @var array
     */
    public $bdayers;

    /**
     * Create a new event instance.
     *
     * @param $bdayers
     */
    public function __construct($bdayers)
    {
        $this->bdayers = $bdayers;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
