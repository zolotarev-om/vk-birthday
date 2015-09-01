<?php

class MainTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var App\User
     */
    private $user;
    /**
     * @var \Mockery\Mock
     */
    private $mockIndex;
    /**
     * @var \Mockery\Mock
     */
    private $mockSetting;

    public function setUp()
    {
        parent::setUp();

        $this->user = App\User::whereId(1)->first();

        $this->mockIndex = Mockery::mock(\App\Http\Controllers\IndexController::class)->makePartial();
        $this->mockIndex->shouldReceive('index')->andReturn(view('index', ['latest' => [], 'upcoming' => []]));

        $this->mockSetting = Mockery::mock(\App\Http\Controllers\SettingController::class)->makePartial();
        $this->mockSetting->shouldReceive('index')->andReturn(view('setting', ['message' => [], 'token' => '']));

        $this->app->instance('App\Http\Controllers\IndexController', $this->mockIndex);
        $this->app->instance('App\Http\Controllers\SettingController', $this->mockSetting);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testRedirectFromMainPageIfNotLoggedIn()
    {
        $this->visit('/')->seePageIs(route('login'));
    }

    public function testSeeMainPageIfLoggedIn()
    {
        $this->actingAs($this->user)->visit(route('main'))->seePageIs(route('main'));
    }

    public function testRedirectFromSettingPageIfNotLoggedIn()
    {
        $this->visit(route('setting'))->seePageIs(route('login'));
    }

    public function testSeeSettingPageIfLoggedIn()
    {
        $this->actingAs($this->user)->visit(route('setting'))->seePageIs(route('setting'));
    }

    public function testErrorPage()
    {
        $this->get(Faker\Factory::create()->slug())->seeStatusCode(404);
    }
}
