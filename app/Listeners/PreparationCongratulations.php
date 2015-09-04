<?php

namespace App\Listeners;

use App\Events\SendMessageToFriendsWhoseBirthday;
use App\Events\ThereFriendsForCongratulations;
use App\Http\Controllers\IndexController;
use App\Repositories\MessageRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreparationCongratulations implements ShouldQueue
{
    /**
     * @var MessageRepository
     */
    private $messageRep;

    /**
     * Create the event listener.
     *
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRep = $messageRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ThereFriendsForCongratulations  $event
     * @return void
     */
    public function handle(ThereFriendsForCongratulations $event)
    {
        foreach($event->bdayers as $friend){
            $message = $this->messageRep->getRandomMessage();
            \Event::fire(new SendMessageToFriendsWhoseBirthday($message, $friend));
        }
    }
}
