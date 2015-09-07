<?php

namespace App\Listeners;

use App\Events\SendMessageToFriendsWhoseBirthday;
use App\Repositories\GratterRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddSendedMessageToDB implements ShouldQueue
{
    /**
     * @var GratterRepository
     */
    private $gratterRep;

    /**
     * Create the event listener.
     *
     * @param GratterRepository $gratterRepository
     */
    public function __construct(GratterRepository $gratterRepository)
    {
        $this->gratterRep = $gratterRepository;
    }

    /**
     * Handle the event.
     *
     * @param  SendMessageToFriendsWhoseBirthday  $event
     * @return void
     */
    public function handle(SendMessageToFriendsWhoseBirthday $event)
    {
        $this->gratterRep->addSendedGratters($event->friend['vk_id'], key($event->message));
    }
}
