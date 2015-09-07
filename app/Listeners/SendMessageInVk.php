<?php

namespace App\Listeners;

use App\Events\SendMessageToFriendsWhoseBirthday;
use App\Http\Controllers\ReqApiController;
use App\Repositories\UserRepository;

class SendMessageInVk
{
    /**
     * @var ReqApiController
     */
    private $reqApi;

    /**
     * @var UserRepository
     */
    private $userRep;

    /**
     * Create the event listener.
     *
     * @param ReqApiController $reqApiController
     * @param UserRepository   $userRepository
     */
    public function __construct(ReqApiController $reqApiController, UserRepository $userRepository)
    {
        $this->reqApi = $reqApiController;
        $this->userRep = $userRepository;

        $token = $this->userRep->getToken();
        $uid = $this->userRep->getUid();
        $this->reqApi->setup($token, $uid);
    }

    /**
     * Handle the event.
     *
     * @param  SendMessageToFriendsWhoseBirthday $event
     *
     * @return void
     */
    public function handle(SendMessageToFriendsWhoseBirthday $event)
    {
        if ($event->friend['can_post'] == 1) {
            $this->reqApi->sendToWall($event->friend['vk_id'], current($event->message));
        } elseif ($event->friend['can_write_private_message'] == 1) {
            $this->reqApi->sendToPrivate($event->friend['vk_id'], current($event->message));
        }
    }
}
