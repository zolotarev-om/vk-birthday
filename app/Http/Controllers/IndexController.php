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

        $data = [];
        $data['latest'] = $this->formatLatestGratters();
        $data['upcoming'] = $this->bday->upcomingBday();

        return view('index')->with($data);
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
