<?php

class ReqApiControllerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var \App\Http\Controllers\ReqApiController
     */
    private $class;
    /**
     * @var \Mockery\Mock
     */
    private $mockVK;

    public function setUp()
    {
        parent::setUp();

        $this->mockVK = Mockery::mock(\getjump\Vk\Core::class);

        $this->class = new \App\Http\Controllers\ReqApiController($this->mockVK);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testGetFriends()
    {
        $data = [
            0  => (object)([
                'id'                        => 30786,
                'first_name'                => 'Анна',
                'last_name'                 => 'Рыбалкина',
                'photo_100'                 => 'http://cs622723.vk.me/v622723786/2b573/SevTLW4ZTfo.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
            ]),
            1  => (object)([
                'id'                        => 53458,
                'first_name'                => 'Серёжа',
                'last_name'                 => 'Блохин',
                'bdate'                     => '24.2.1982',
                'photo_100'                 => 'http://cs421724.vk.me/v421724458/79d6/v2BDi4qCG_4.jpg',
                'can_post'                  => 1,
                'can_write_private_message' => 1,
            ]),
            2  => (object)([
                'id'                        => 243637,
                'first_name'                => 'Анастасия',
                'last_name'                 => 'Японова',
                'photo_100'                 => 'http://cs605816.vk.me/v605816637/20002/LwVj6DanOdA.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
            ]),
            3  => (object)([
                'id'                        => 337927,
                'first_name'                => 'Андрей',
                'last_name'                 => 'Аасаметс',
                'bdate'                     => '1.2.1965',
                'photo_100'                 => 'http://cs629300.vk.me/v629300927/1619/fIuUp8FPoPo.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
            ]),
            4  => (object)([
                'id'                        => 408031,
                'first_name'                => 'Павел',
                'last_name'                 => 'Рассудов',
                'bdate'                     => '12.4.1983',
                'photo_100'                 => 'http://cs413124.vk.me/v413124031/1f48/bcm7FRCR8C4.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 0,
            ]),
            5  => (object)([
                'id'                        => 685521,
                'first_name'                => 'Андрей',
                'last_name'                 => 'Колесников',
                'bdate'                     => '21.12.1985',
                'photo_100'                 => 'http://cs410528.vk.me/v410528521/3cc5/tshcI3KFsPs.jpg',
                'can_post'                  => 1,
                'can_write_private_message' => 1,
            ]),
            6  => (object)([
                'id'                        => 717522,
                'first_name'                => 'Александр',
                'last_name'                 => 'Александров',
                'bdate'                     => '21.11',
                'photo_100'                 => 'http://cs625331.vk.me/v625331522/16785/MWquKxoK_8Q.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
            ]),
            7  => (object)([
                'id'                        => 726350,
                'first_name'                => 'Антон',
                'last_name'                 => 'Вакуленко',
                'photo_100'                 => 'http://cs618519.vk.me/v618519350/11293/1sad5BQGG5k.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
            ]),
            8  => (object)([
                'id'                        => 821131,
                'first_name'                => 'Сергей',
                'last_name'                 => 'Тучков',
                'bdate'                     => '15.5',
                'photo_100'                 => 'http://cs621724.vk.me/v621724131/d45f/MqeX-2XzXX4.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
            ]),
            9  => (object)([
                'id'                        => 2626489,
                'first_name'                => 'Алексей',
                'last_name'                 => 'Ярошевский',
                'deactivated'               => 'deleted',
                'photo_100'                 => 'http://vk.com/images/deactivated_100.png',
                'can_write_private_message' => 0,
                'can_post'                  => 0,
            ]),
            10 => (object)([
                'id'                        => 8865620,
                'first_name'                => 'Виктор',
                'last_name'                 => 'Лубенец',
                'deactivated'               => 'banned',
                'photo_100'                 => 'http://vk.com/images/deactivated_100.png',
                'can_write_private_message' => 0,
                'can_post'                  => 0,
            ]),
        ];

        $this->mockVK
            ->shouldReceive('request')
            ->once()
            ->with('friends.get', Mockery::hasKey('user_id', 'order', 'fields', 'count'))
            ->andReturn(new getjump\Vk\Response\Response($data));

        $resp = $this->class->getFriends();

        $this->assertNotSameSize($resp, $data);
        $this->assertCount(5, $resp);
    }

    public function testSetup()
    {
        $this->mockVK->shouldReceive('apiVersion->setToken')->withAnyArgs()->once()->andReturnNull();

        $resp = $this->class->setup('111', '111');
        $this->assertInstanceOf(App\Http\Controllers\ReqApiController::class, $resp);
    }

    public function testFetchNameAndAvatar()
    {
        $data = [
            0 => (object)([
                'uid'        => 78017801,
                'first_name' => 'Алёна',
                'last_name'  => '***',
                'photo_100'  => 'http://cs412717.vk.me/v412717801/802f/9pEVYZXWIPs.jpg',
            ]),
        ];

        $this->mockVK
            ->shouldReceive('request')
            ->once()
            ->with('users.get', Mockery::hasKey('user_ids', 'fields'))
            ->andReturn(new getjump\Vk\Response\Response($data));

        $resp = $this->class->fetchNameAndAvatar('78017801');
        $this->assertArrayHasKey('name', $resp);
        $this->assertArrayHasKey('avatar', $resp);
    }

    public function testSendToPrivate()
    {
        putenv('APP_ENV=prod');
        putenv('APP_DEBUG=false');

        $this->mockVK
            ->shouldReceive('request')
            ->once()
            ->with('messages.send', Mockery::hasKey('user_id', 'message'))
            ->andReturnSelf();
        $this->mockVK->shouldReceive('execute')->withAnyArgs()->andReturnNull();

        $res = $this->class->sendToPrivate('217740281', 'message');
        $this->assertTrue($res);
    }

    public function testSendToWall()
    {
        putenv('APP_ENV=prod');
        putenv('APP_DEBUG=false');

        $this->mockVK
            ->shouldReceive('request')
            ->once()
            ->with('wall.post', Mockery::hasKey('owner_id', 'message'))
            ->andReturnSelf();
        $this->mockVK->shouldReceive('execute')->withAnyArgs()->andReturnNull();

        $res = $this->class->sendToWall('217740281', 'message');
        $this->assertTrue($res);
    }
}
