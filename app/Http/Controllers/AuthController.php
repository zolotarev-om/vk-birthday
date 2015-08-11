<?php

namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use Session;
use Socialite;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string provider name
     */
    private $provider;

    /**
     * DI
     *
     * @param UserRepository $users
     * @param Request        $request
     */
    public function __construct(
        UserRepository $users,
        Request $request
    ) {
        $this->users = $users;
        $this->request = $request;
    }


    /**
     * Set or Get provider name from request and process login
     *
     * @param null|string $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function preLogin($provider = null)
    {
        if (!empty($provider)) {
            $this->provider = $provider;
            Session::push('provider', $provider);
        } else {
            $this->provider = Session::pull('provider');
        }
        return $this->processLogin($this->request->all());
    }

    /**
     * If need authorize user, find or create in DB, login and redirect him
     *
     * @param $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processLogin($request)
    {
        if (!$request) {
            return $this->getAuthorizationFirst();
        }
        $user = $this->users->findByUidOrCreate($this->getSocialUser(), $this->provider);

        Auth::login($user, true);

        return $this->userHasLoggedIn();
    }


    /**
     * Redirect user to OAuth via Socialite
     *
     * @return redirect
     */
    private function getAuthorizationFirst()
    {
        return Socialite::driver($this->provider)->redirect();
    }


    /**
     * Get user via Socialite
     *
     * @return array
     */
    private function getSocialUser()
    {
        return Socialite::driver($this->provider)->user();
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
     * View login page OR login admin if APP_ENV = local
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
}