<?php

namespace App\Repositories;

use App\Gratter;
use Auth;

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
     *
     *
     */
    private function getGratters()
    {
        if (empty($this->gratters)) {
            $this->gratters = Auth::user()->gratters()->where('year', '=', date('Y'))->get();
        }
    }

    /**
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
     * @param $uid
     *
     * @return mixed
     */
    public function gratterMessageId($uid)
    {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

        $gratters = Auth::user()->gratters()->where('to', '=', $uid)->where('year', '=', date('Y'))->first();

        return $gratters->message_id;
    }

    /**
     * @param int $count
     *
     * @return array
     */
    public function latestGratters($count = 3)
    {
        $count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);

        $myGratter = Gratter::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return $myGratter->take($count)->reverse()->toArray();
    }
}