<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function ($faker) {
    return [
        'active'         => $faker->boolean(80),
        'name'           => $faker->name,
        'username'       => $faker->userName,
        'email'          => $faker->safeEmail,
        'avatar'         => $faker->imageUrl(100, 100),
        'providers_id'   => factory(App\Provider::class)->create()->id,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Provider::class, function ($faker) {
    return [
        'name'  => 'vkontakte',
        'uid'   => $faker->randomNumber(9),
        'token' => $faker->sha256,
    ];
});

$factory->define(App\Message::class, function ($faker) {
    return [
        'text'    => $faker->sentence,
        'user_id' => App\User::all()->random(1)->id,
    ];
});

$factory->define(App\Gratter::class, function ($faker) {
    return [
        'user_id'    => App\User::all()->random(1)->id,
        'to'         => $faker->randomNumber(9),
        'message_id' => App\Message::all()->random(1)->id,
        'year'       => $faker->randomElement([date('Y'), date('Y') - 1]),
    ];
});