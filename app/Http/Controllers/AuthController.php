<?php

namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use App\User;
use Auth;
use Redirect;
use Socialite;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @const Provider name
     */
    const PROVIDER = 'vkontakte';

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * DI
     *
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Redirect user to OAuth via Socialite
     *
     * @return redirect
     */
    public function getAuthorizationFirst()
    {
        return Socialite::with(self::PROVIDER)->redirect();
    }

    /**
     * Login user via Socialite
     *
     * @return redirect
     */
    public function loginSocialUser()
    {
        $user = $this->users->findByUidOrCreate($this->getSocialUser(), self::PROVIDER);

        Auth::login($user, true);

        return $this->userHasLoggedIn();
    }

    /**
     * The user authentication passed, redirect
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function userHasLoggedIn()
    {
        return Redirect::to('/');
    }

    /**
     * Logout user and redirect him
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::route('login');
    }

    /**
     * View login page OR login admin if exist and APP_ENV = local
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function loginPage()
    {
        if (env('APP_ENV') == 'local') {
            $admin = User::whereId(1)->get();
            if (!is_null($admin)) {
                Auth::login($admin[0], true);
                return redirect('/');
            }
        }
        return view('login');
    }

    /**
     * @return mixed
     */
    public function getSocialUser()
    {
        return Socialite::with(self::PROVIDER)->user();
    }
}