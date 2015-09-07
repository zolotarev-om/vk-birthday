<?php

namespace App\Listeners;

use App\Events\ThereFriendsForCongratulations;
use App\Events\UserLoginToMainPage;
use Illuminate\Contracts\Queue\ShouldQueue;

class LaunchProcessCongratulations implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoginToMainPage $event
     *
     * @return void
     */
    public function handle(UserLoginToMainPage $event)
    {
        $bdayers = $event->bday->friendsForCongratulations();
        if (!empty($bdayers)) {
            \Event::fire(new ThereFriendsForCongratulations($bdayers));
        }
    }
}
