<?php

class MainTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $mockIndex = Mockery::mock(App\Http\Controllers\IndexController::class);
        $mockSetting = Mockery::mock(App\Http\Controllers\SettingController::class);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testMainPageRedirectIfNotLoggedIn()
    {
        $this->visit('/')->seePageIs(route('login'));
    }

    public function testMainPageIfLoggedIn()
    {
        $user = App\User::whereId(1)->first();
        $this->actingAs($user)->visit(route('main'))->seePageIs(route('main'));
    }

    public function testSettingPageIfNotLoggedIn()
    {
        $this->visit(route('setting'))->seePageIs(route('login'));
    }

    public function testSettingPageIfLoggedIn()
    {
        $user = App\User::whereId(1)->first();
        $this->actingAs($user)->visit(route('setting'))->seePageIs(route('setting'));
    }

    public function testAnyPageIfNotLoggedIn()
    {
        $this->get(Faker\Factory::create()->slug())->seeStatusCode(404);
    }
}
