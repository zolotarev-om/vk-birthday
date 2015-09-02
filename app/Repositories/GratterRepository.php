<?php

namespace App\Repositories;

use App\Gratter;
use Auth;
use Cache;

/**
 * Class GratterRepository
 * @package App\Repositories
 */
class GratterRepository
{

    /**
     * @var array
     */
    private $gratters;


    /**
     * Check whether the user has sent congratulations to the friend this year
     *
     * @param $uid
     *
     * @return bool
     */
    public function alreadyGratterOrFalse($uid)
    {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

        $this->getGratters();

        $uidGratters = $this->gratters->contains('to', $uid);

        return $uidGratters;
    }

    /**
     * Eager loading sended user gratters in this year
     */
    private function getGratters()
    {
        $this->gratters = Cache::remember('gratters_' . Auth::id(), 60, function () {
            return Auth::user()->gratters()->where('year', '=', date('Y'))->get();
        });
    }

    /**
     * Add sended gratters from user to friends
     *
     * @param $uid
     * @param $messageId
     */
    public function addSendedGratters($uid, $messageId)
    {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);
        $messageId = filter_var($messageId, FILTER_SANITIZE_NUMBER_INT);

        $gratter = new Gratter;
        $gratter->user_id = Auth::user()->id;
        $gratter->to = $uid;
        $gratter->message_id = $messageId;
        $gratter->year = date('Y');
        $gratter->save();
    }

    /**
     * Get the message id gratters
     *
     * @param $uid
     *
     * @return int
     */
    public function gratterMessageId($uid)
    {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

        $gratters = Cache::remember('gratters_to_' . Auth::id() . '_' . $uid, 60, function () use ($uid) {
            return Auth::user()->gratters()->where('to', '=', $uid)->where('year', '=', date('Y'))->first();
        });

        return $gratters->message_id;
    }

    /**
     * Get latest X gratters from user
     *
     * @param int $count
     *
     * @return array
     */
    public function latestGratters($count = 3)
    {
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);

        $myGratter = Cache::remember('latest_gratters_' . Auth::id(), 10, function () {
            return Gratter::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        });

        return $myGratter->take($count)->reverse()->toArray();
    }
}