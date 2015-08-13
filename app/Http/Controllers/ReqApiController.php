<?php

namespace App\Http\Controllers;

use getjump\Vk;

/**
 * Class ReqApiController
 * @package App\Http\Controllers
 */
class ReqApiController extends Controller
{
    /**
     * @var int VK User id
     */
    private $user;

    /**
     * @var int VK User accessToken
     */
    private $token;

    /**
     * @var Vk\Core Getjump VK instance
     */
    private $vk;
    /**
     * @var array User friends
     */
    private $friends;

    /**
     * Setup "guest" VK instance
     */
    public function __construct()
    {
        $this->vk = Vk\Core::getInstance();
    }

    /**
     * Execute fetchFriendsApi and return user's friends array
     *
     * @return array
     */
    public function getFriends()
    {
        $this->fetchFriendsApi();
        return $this->friends;
    }

    /**
     * Fetch user's friends via VK api
     *
     * @return array|false
     */
    private function fetchFriendsApi()
    {
        $this->vk->request('friends.get', [
            'user_id' => $this->user,
            'order '  => 'hints',
            'fields'  => 'photo_100,bdate,can_write_private_message,can_post',
            'count'   => 500,
        ])
            ->each(function ($i, $friend) {
                if ($this->suitableFriend($friend) !== false) {
                    $friend->vk_id = $friend->id;
                    unset($friend->id);
                    $friend->bdate = $this->formatBDate($friend->bdate);
                    unset($friend->online);
                    unset($friend->lists);

                    $count = count($this->friends) + 1;
                    return $this->friends[$count] = (array)$friend;
                } else {
                    return false;
                }
            });
    }

    /**
     * Check deactivated friend, set birthday and i can write message
     *
     * @param $friend object One friend object from VK api
     *
     * @return bool|object
     */
    private function suitableFriend($friend)
    {
        if (!isset($friend->deactivated) && isset($friend->bdate) && ($friend->can_post == 1 || $friend->can_write_private_message == 1)) {
            return $friend;
        } else {
            return false;
        }
    }

    /**
     * Format date to DD.MM.YYYY (if year not exist to DD.MM.0000)
     *
     * @param $bday float
     *
     * @return bool|string
     */
    private function formatBDate($bday)
    {
        if (preg_match('/([0]?[1-9]|[1|2][0-9]|[3][0|1])[.]([0]?[1-9]|[1][0-2])[.]([0-9]{4})/', $bday) == 0) {
            $bday = $bday . '.0000';
            return $bday;
        } elseif (preg_match('/([0]?[1-9]|[1|2][0-9]|[3][0|1])[.]([0]?[1-9]|[1][0-2])[.]([0-9]{4})/',
                $bday) === false
        ) {
            return false;
        } else {
            return $bday;
        }
    }

    /**
     * Setup VK instance access token, userid and api version
     *
     * @param $vkToken  int
     * @param $vkUserId int
     * @param $apiVer   float
     *
     * @return $this
     */
    public function setup($vkToken, $vkUserId, $apiVer = 5.35)
    {
        $vkToken = filter_var($vkToken, FILTER_SANITIZE_STRING);
        $vkUserId = filter_var($vkUserId, FILTER_SANITIZE_NUMBER_INT);
        $apiVer = filter_var($apiVer, FILTER_SANITIZE_NUMBER_FLOAT);

        $this->vk = Vk\Core::getInstance()->apiVersion($apiVer)->setToken($vkToken);
        $this->user = $vkUserId;
        return $this;
    }

    /**
     * Fetch and return user name and avatar by user-id
     *
     * @param $uid int user id
     *
     * @return array|false
     */
    public function fetchNameAndAvatar($uid)
    {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

        $obj = $this->vk->request('users.get', [
            'user_ids' => $uid,
            'fields'   => 'photo_100',
        ])
            ->get();

        $data = ['avatar' => $obj->photo_100, 'name' => $obj->first_name . ' ' . $obj->last_name];

        return $data;
    }

    /**
     * If not env not local or debug not false send message to user wall, else dump to screen
     *
     * @param $uid int user id
     * @param $msg string congratulation message
     */
    public function sendToWall($uid, $msg)
    {
        if (env('APP_ENV') == 'local' || env('APP_DEBUG') == 'true') {
            var_dump("WallMsg: $msg; To: $uid");
        } else {
            $this->vk->request('wall.post', ['owner_id' => $uid, 'message' => $msg])->execute();
        }
    }

    /**
     * If not env not local or debug not false send message to user PM, else dump to screen
     *
     * @param $uid int user id
     * @param $msg string congratulation message
     */
    public function sendToPrivate($uid, $msg)
    {
        if (env('APP_ENV') == 'local' || env('APP_DEBUG') == 'true') {
            var_dump("PrivateMsg: $msg; To: $uid");
        } else {
            $this->vk->request('messages.send', ['user_id' => $uid, 'message' => $msg])->execute();
        }
    }
}
