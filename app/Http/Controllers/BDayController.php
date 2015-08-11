<?php

namespace App\Http\Controllers;

use App\Repositories\GratterRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;

/**
 * Class BDayController
 * @package App\Http\Controllers
 */
class BDayController extends Controller
{
    /**
     * @var array Users friends
     */
    private $friends;

    /**
     * DI, setup VK instance and get users friends
     *
     * @param ReqApiController  $reqApiController
     * @param UserRepository    $userRepository
     * @param GratterRepository $gratterRepository
     */
    public function __construct(
        ReqApiController $reqApiController,
        UserRepository $userRepository,
        GratterRepository $gratterRepository
    ) {
        $this->reqApi = $reqApiController;
        $this->userRep = $userRepository;
        $this->gratterRep = $gratterRepository;

        $token = $this->userRep->getToken();
        $uid = $this->userRep->getUid();
        $this->friends = $this->reqApi->setup($token, $uid)->getFriends();
    }

    /**
     * Finds users whose birthday today and we have not yet congratulated then adds them to array
     *
     * @return array
     */
    public function friendsForCongratulations()
    {
        $bdayers = [];
        foreach ($this->friends as $friend) {
            $hasBDay = $this->hasBDay($friend['bdate']);
            if ($hasBDay) {
                $alreadyGratter = $this->gratterRep->alreadyGratterOrFalse($friend['vk_id']);
                if (!$alreadyGratter) {
                    $bdayers[count($bdayers)] = $friend;
                }
            }
        }
        return $bdayers;
    }

    /**
     * This friend has a birthday today?
     *
     * @param $date int date of birth
     *
     * @return bool
     */
    private function hasBDay($date)
    {
        $date_user = Carbon::createFromFormat('d.m.Y', $date);
        $date_now = Carbon::createFromDate();
        if ($date_user->isBirthday($date_now)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Fetch, Prepare and Return friends who have a birthday in the future
     *
     * @param $count int how many friends need get
     *
     * @return array
     */
    public function upcomingBday($count = 3)
    {
        $sortedFriends = $this->sortFriends();

        $upcomingBday = $this->getOnlyUpcomingBday($sortedFriends);

        array_splice($upcomingBday, $count);

        $formattedUpcomingBday = $this->formatUpcomingBday($upcomingBday);

        return $formattedUpcomingBday;
    }

    /**
     * Sorting an array of friends by birthdays
     *
     * @return array
     */
    private function sortFriends()
    {
        $sortFriends = $this->friends;
        uasort($sortFriends, [$this, 'sortDate']);
        return $sortFriends;
    }

    /**
     * Returns friends who have a birthday in the future
     *
     * @param $sortedFriends array
     *
     * @return array
     */
    private function getOnlyUpcomingBday($sortedFriends)
    {
        $upcomingBday = [];
        foreach ($sortedFriends as $friend) {
            $date = Carbon::createFromFormat('d.m.Y', $friend['bdate']);
            $date->year(date('Y'));

            if ($date->gt(Carbon::now())) {
                $cnt = count($upcomingBday);
                $upcomingBday[$cnt] = $friend;
            }
        }
        return $upcomingBday;
    }

    /**
     * Format array of upcoming bday for view on site
     *
     * @param $upcomingBday array
     *
     * @return array
     */
    private function formatUpcomingBday($upcomingBday)
    {
        $upcoming = [];
        foreach ($upcomingBday as $id => $obj) {
            $obj['name'] = $obj['first_name'] . ' ' . $obj['last_name'];
            $date = Carbon::createFromFormat('d.m.Y', $obj['bdate']);
            $obj['bdate'] = $date->format('d.m');
            $obj['avatar'] = $obj['photo_100'];
            unset($obj['first_name'], $obj['last_name'], $obj['photo_100']);
            unset($upcoming[$id]);
            $upcoming[$id] = $obj;
        }
        return $upcoming;
    }

    /**
     * Func to a<>b sort friend by date of birth
     *
     * @param $a array one friend array
     * @param $b array other friend array
     *
     * @return int
     */
    private function sortDate($a, $b)
    {
        $a_date = Carbon::createFromFormat('d.m.Y', $a['bdate']);
        $a_date->year(date('Y'));
        $b_date = Carbon::createFromFormat('d.m.Y', $b['bdate']);
        $b_date->year(date('Y'));

        if ($a_date->gt($b_date)) {
            return 1;
        } else {
            return -1;
        }
    }
}
