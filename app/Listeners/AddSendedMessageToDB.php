<?php

namespace App\Listeners;

use App\Events\SendMessageToFriendsWhoseBirthday;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddSendedMessageToDB
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendMessageToFriendsWhoseBirthday  $event
     * @return void
     */
    public function handle(SendMessageToFriendsWhoseBirthday $event)
    {
        //
    }
}
