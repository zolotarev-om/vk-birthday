<?php


/**
 * Class AuthControllerTest
 */
class AuthControllerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\WithoutMiddleware;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var AuthController
     */
    protected $class;
    /**
     * @var \App\Repositories\UserRepository
     */
    private $mockRep;
    /**
     * @var AuthController
     */
    private $mockAuth;
    /**
     * @var App\User
     */
    private $user;
    private $soc;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = App\User::whereId(1)->first();

        $this->mockRep = Mockery::mock(App\Repositories\UserRepository::class);
        $this->mockRep->shouldReceive('findByUidOrCreate')->andReturn($this->user);

        $this->mockAuth = Mockery::mock('App\Http\Controllers\AuthController[getSocialUser,getAuthorizationFirst]',[$this->mockRep]);
        $this->mockAuth->shouldReceive('getSocialUser')->andReturn($this->user);
        $this->mockAuth->shouldReceive('getAuthorizationFirst')->andReturn(redirect('http://oauth.vk.com'));
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
        $resp = $this->mockAuth->loginSocialUser();
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $this->assertEquals(env('APP_URL'), $resp->getTargetUrl());
    }

    public function testProcessVkAuth()
    {
        $resp = $this->mockAuth->getAuthorizationFirst();
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $this->assertEquals('http://oauth.vk.com', $resp->getTargetUrl());
    }

    public function testLogout()
    {
        Auth::shouldReceive('logout')
            ->once()
            ->withNoArgs();
        $resp = $this->mockAuth->logout();
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $this->assertEquals(route('login'), $resp->getTargetUrl());
    }
}
