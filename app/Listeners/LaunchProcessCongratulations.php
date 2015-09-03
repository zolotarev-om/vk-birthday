<?php

namespace App\Listeners;

use App\Events\UserLoginToMainPage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LaunchProcessCongratulations
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
     * @param  UserLoginToMainPage  $event
     * @return void
     */
    public function handle(UserLoginToMainPage $event)
    {
        //
    }
}
