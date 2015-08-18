<?php


namespace App\Repositories;

use App\Provider;
use App\User;
use Auth;
use Cache;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * @var array
     */
    private $providers;

    /**
     * Looking for the user, if it is not creating it. Check whether it is necessary update its data.
     *
     * @param $userData
     * @param $provider
     *
     * @return User
     */
    public function findByUidOrCreate($userData, $provider)
    {
        $user = Cache::remember('user_' . $userData->id, 60, function ($provider, $userData) {
            return User::whereHas('providers', function ($query) use ($provider, $userData) {
                $query->where('name', '=', $provider)->where('uid', '=', $userData->id);
            })->first();
        });

        if (empty($user)) {
            $providers = new Provider;
            $providers->name = $provider;
            $providers->uid = $userData->id;
            $providers->save();

            $user = new User;
            $user->active = 1;
            $user->name = $userData->name;
            $user->username = $userData->nickname;
            $user->email = $userData->email;
            $user->avatar = $userData->avatar;
            $user->providers()->associate($providers);
            $user->save();
        }

        $this->checkIfUserNeedsUpdating($userData, $user);

        return $user;
    }


    /**
     * Check whether it is necessary update user data
     *
     * @param $userData User
     * @param $user User
     */
    public function checkIfUserNeedsUpdating($userData, $user)
    {
        $socialData = [
            'avatar'   => $userData->avatar,
            'email'    => $userData->email,
            'name'     => $userData->name,
            'username' => $userData->nickname,
        ];

        $dbData = [
            'avatar'   => $user->avatar,
            'email'    => $user->email,
            'name'     => $user->name,
            'username' => $user->username,
        ];

        if (!empty(array_diff($socialData, $dbData))) {
            $user->avatar = $userData->avatar;
            $user->email = $userData->email;
            $user->name = $userData->name;
            $user->username = $userData->nickname;

            $user->save();
        }
    }

    /**
     * Get VK token authorized user
     *
     * @return string
     */
    public function getToken()
    {
        $this->getProviders();
        $token = $this->providers->first()->token;
        return $token;
    }

    /**
     * Once getting the providers relationship
     *
     * TODO: rewrite method to use Cache
     */
    private function getProviders()
    {
        $this->providers = Cache::remember('providers_' . Auth::id(), 10, function () {
            return Auth::user()->providers()->get();
        });
    }

    /**
     * Set/Update VK token authorized user
     *
     * @param $token
     *
     * @return bool
     */
    public function setOrUpdateToken($token)
    {
        if (preg_match("/([a-z0-9]{85})+/", $token)) {
            $user = Auth::user()->providers()->first();
            $user->token = $token;
            $user->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get VK uid authorized user
     *
     * @return int
     */
    public function getUid()
    {
        $this->getProviders();
        $uid = $this->providers->first()->uid;
        return $uid;
    }
}