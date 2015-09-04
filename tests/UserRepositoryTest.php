<?php

class UserRepositoryTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var App\User
     */
    private $user;

    /**
     * @var App\Repositories\UserRepository
     */
    private $class;

    public function setUp()
    {
        parent::setUp();

        factory(App\User::class, 3)->create();

        $this->user = App\User::all()->first();
        $this->be($this->user);

        $this->class = new \App\Repositories\UserRepository();
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testFindByUidOrCreate()
    {
        $provider = 'vkontakte';
        $user = (object)([
            'id'       => 217740281,
            'nickname' => 'zolotarev_om',
            'name'     => 'Олег Золотарев',
            'email'    => 'zolotarev.om@gmail.com',
            'avatar'   => 'http://cs623928.vk.me/v623928281/27759/LE5NVAigAbs.jpg',
        ]);
        $this->class->findByUidOrCreate($user, $provider);
        $this->seeInDatabase('users', [
            'name'     => $user->name,
            'username' => $user->nickname,
            'email'    => $user->email,
            'avatar'   => $user->avatar,
        ]);
        $this->seeInDatabase('providers', [
            'name' => $provider,
            'uid'  => $user->id,
        ]);
    }

    public function testGetToken()
    {
        $token = $this->user->providers()->first()->token;
        $resp = $this->class->getToken();
        $this->assertEquals($token, $resp);
    }

    public function testGetUid()
    {
        $uid = $this->user->providers()->first()->uid;
        $resp = $this->class->getUid();
        $this->assertEquals($uid, $resp);
    }

    public function testSetOrUpdateToken()
    {
        $newToken = Faker\Factory::create()->regexify('[a-z0-9]{85}');
        $resp = $this->class->setOrUpdateToken($newToken);
        $tokenInDb = $this->user->providers()->first()->token;
        $this->assertTrue($resp);
        $this->assertEquals($newToken, $tokenInDb);
    }
}
