<?php

namespace App\Listeners;

use App\Events\ThereFriendsForCongratulations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreparationCongratulations
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
     * @param  ThereFriendsForCongratulations  $event
     * @return void
     */
    public function handle(ThereFriendsForCongratulations $event)
    {
        //
    }
}
