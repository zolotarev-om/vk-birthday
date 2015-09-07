<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserLoginToMainPage'                => [
            'App\Listeners\LaunchProcessCongratulations',
        ],
        'App\Events\ThereFriendsForCongratulations'     => [
            'App\Listeners\PreparationCongratulations',
        ],
        'App\Events\SendMessageToFriendsWhoseBirthday'     => [
            'App\Listeners\SendMessageInVk',
            'App\Listeners\AddSendedMessageToDB',
        ],
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'SocialiteProviders\VKontakte\VKontakteExtendSocialite@handle',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
