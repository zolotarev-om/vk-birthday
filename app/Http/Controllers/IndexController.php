<?php

namespace App\Http\Controllers;


use App\Events\UserLoginToMainPage;
use App\Repositories\GratterRepository;
use App\Repositories\MessageRepository;
use Carbon\Carbon;
use Event;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * @var ReqApiController
     */
    private $reqApi;

    /**
     * @var MessageRepository
     */
    private $messageRep;

    /**
     * @var GratterRepository
     */
    private $gratterRep;
    /**
     * @var BDayController
     */
    private $bday;

    /**
     * DI
     *
     * @param ReqApiController  $reqApiController
     * @param BDayController    $BDayController
     * @param MessageRepository $messageRepository
     * @param GratterRepository $gratterRepository
     */
    public function __construct(
        ReqApiController $reqApiController,
        MessageRepository $messageRepository,
        GratterRepository $gratterRepository,
        BDayController $BDayController
    ) {
        $this->bday = $BDayController;
        $this->reqApi = $reqApiController;
        $this->messageRep = $messageRepository;
        $this->gratterRep = $gratterRepository;
    }

    /**
     * Process congratulations and render main page with latest and upcoming bday
     *
     * @return $this
     */
    public function index()
    {
        Event::fire(new UserLoginToMainPage($this->bday));
        //$this->processCongratulations();

        $data = [];
        $data['latest'] = $this->formatLatestGratters();
        $data['upcoming'] = $this->bday->upcomingBday();

        return view('index')->with($data);
    }

    /**
     *  Get friends for congratulations, if they have - get random message,
     *  send message to friend and add sended gratters
     */
    private function processCongratulations()
    {
        $bdayers = $this->bday->friendsForCongratulations();

        if (!empty($bdayers)) {
            foreach ($bdayers as $friend) {
                $msg = $this->messageRep->getRandomMessage();
                $this->sendMessage($friend, current($msg)); //text message
                $this->gratterRep->addSendedGratters($friend['vk_id'], key($msg)); // key(id) message
            }
        }
    }

    /**
     * Select destination to send message and execute VK api to this
     *
     * @param $user array
     * @param $msg  string
     *
     * @return bool
     */
    private function sendMessage($user, $msg)
    {
        if ($user['can_post'] == 1) {
            $this->reqApi->sendToWall($user['vk_id'], $msg);
            return true;
        } elseif ($user['can_write_private_message'] == 1) {
            $this->reqApi->sendToPrivate($user['vk_id'], $msg);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Formatting last gratters to be displayed on the main page
     *
     * @return array
     */
    private function formatLatestGratters()
    {
        $latest = $this->gratterRep->latestGratters();
        foreach ($latest as $id => $obj) {
            $nameAndAvatar = $this->reqApi->fetchNameAndAvatar($obj['to']);
            $obj['message'] = $this->messageRep->getMessageTextById($obj['message_id']);
            $obj['name'] = $nameAndAvatar['name'];
            $obj['avatar'] = $nameAndAvatar['avatar'];
            $date = Carbon::createFromFormat('Y-m-d H:m:s', $obj['created_at']);
            $obj['created_at'] = $date->format('d.m');
            unset($obj['message_id'], $obj['user_id'], $obj['updated_at']);
            unset($latest[$id]);
            $latest[$id] = $obj;
        }
        return $latest;
    }
}
