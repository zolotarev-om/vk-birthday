<?php


use App\Http\Controllers\AuthController;

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

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = App\User::whereId(1)->first();

        $this->mockRep = $this->getMockBuilder(App\Repositories\UserRepository::class)->getMock();
        $this->mockRep->method('findByUidOrCreate')->willReturn($this->user);

        $this->mockAuth = $this->getMockBuilder(AuthController::class)
            ->setMethods(['getSocialUser', 'getAuthorizationFirst'])
            ->setConstructorArgs([$this->mockRep])
            ->getMock();
        $this->mockAuth->method('getSocialUser')->willReturn($this->user);

    }

    /**
     *
     */
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
    }
}
