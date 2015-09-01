<?php


/**
 * Class AuthControllerTest
 */
class AuthControllerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\WithoutMiddleware;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var \Mockery\Mock
     */
    private $mockRep;
    /**
     * @var \Mockery\Mock
     */
    private $mockAuth;
    /**
     * @var App\User
     */
    private $user;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = App\User::whereId(1)->first();

        $this->mockRep = Mockery::mock(App\Repositories\UserRepository::class);
        $this->mockRep->shouldReceive('findByUidOrCreate')->andReturn($this->user);

        $this->mockAuth = Mockery::mock('App\Http\Controllers\AuthController[getSocialUser,getAuthorizationFirst]',
            [$this->mockRep]);
        $this->mockAuth->shouldReceive('getSocialUser')->andReturn($this->user);
        $this->mockAuth->shouldReceive('getAuthorizationFirst')->andReturn(redirect('http://oauth.vk.com'));

        $this->app->instance('App\Repositories\UserRepository', $this->mockRep);
        $this->app->instance('App\Http\Controllers\AuthController', $this->mockAuth);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testSeeLoginPage()
    {
        $this->action('GET', 'AuthController@loginPage');
        $this->see('Войти');
    }

    public function testLoginSocialUser()
    {
        Auth::shouldReceive('login')
            ->once()
            ->with($this->user, true);
        $this->action('GET', 'AuthController@loginSocialUser');
        $this->assertRedirectedTo('/');
    }

    public function testProcessVkAuth()
    {
        $this->action('GET', 'AuthController@getAuthorizationFirst');
        $this->assertRedirectedTo('http://oauth.vk.com');
    }

    public function testLogout()
    {
        Auth::shouldReceive('logout')
            ->once()
            ->withNoArgs();
        $this->action('GET', 'AuthController@logout');
        $this->assertRedirectedToRoute('login');
    }
}
