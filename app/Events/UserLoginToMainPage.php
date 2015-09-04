<?php

namespace App\Events;

use App\Http\Controllers\BDayController;
use Illuminate\Queue\SerializesModels;

class UserLoginToMainPage extends Event
{
    use SerializesModels;

    /**
     * @var BDayController
     */
    public $bday;


    /**
     * Create a new event instance.
     *
     * @param BDayController $BDayController
     */
    public function __construct(BDayController $BDayController)
    {
        $this->bday = $BDayController;
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
