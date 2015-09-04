<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMessageToFriendsWhoseBirthday extends Event
{
    use SerializesModels;

    /**
     * @var array
     */
    public $message;

    /**
     * @var array
     */
    public $friend;

    /**
     * Create a new event instance.
     *
     * @param $message
     * @param $friend
     */
    public function __construct($message, $friend)
    {
        $this->message = $message;
        $this->friend = $friend;
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
