<?php

class BDayControllerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var Mockery\Mock
     */
    private $mockReqApi;
    /**
     * @var Mockery\Mock
     */
    private $mockUserRep;
    /**
     * @var Mockery\Mock
     */
    private $mockGratRep;
    /**
     * @var \App\Http\Controllers\BDayController
     */
    private $class;

    public function setUp()
    {
        parent::setUp();

        $friends = [
            1 => [
                'first_name'                => 'Серёжа',
                'last_name'                 => 'Блохин',
                'bdate'                     => (date('d') + 1) . '.' . date('n.Y'),
                'photo_100'                 => 'http://cs421724.vk.me/v421724458/79d6/v2BDi4qCG_4.jpg',
                'can_post'                  => 1,
                'can_write_private_message' => 1,
                'vk_id'                     => 53458,
            ],
            2 => [
                'first_name'                => 'Оксана',
                'last_name'                 => 'Гуда',
                'bdate'                     => date('d.m') . '.1986',
                'photo_100'                 => 'http://cs629424.vk.me/v629424086/80c2/qi-TrhMdPK0.jpg',
                'can_post'                  => 1,
                'can_write_private_message' => 1,
                'vk_id'                     => 161086,
            ],
            3 => [
                'first_name'                => 'Андрей',
                'last_name'                 => 'Аасаметс',
                'bdate'                     => date('d.n') . '.0000',
                'photo_100'                 => 'http://cs629300.vk.me/v629300927/1619/fIuUp8FPoPo.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
                'vk_id'                     => 337927,
            ],
            4 => [
                'first_name'                => 'Павел',
                'last_name'                 => 'Рассудов',
                'bdate'                     => (date('d') + 2) . '.' . (date('m') + 1) . '.' . date('y'),
                'photo_100'                 => 'http://cs413124.vk.me/v413124031/1f48/bcm7FRCR8C4.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'vk_id'                     => 408031,
            ],
            5 => [
                'first_name'                => 'Андрей',
                'last_name'                 => 'Колесников',
                'bdate'                     => (date('d') - 1) . '.' . date('m') . '.' . date('y'),
                'photo_100'                 => 'http://cs410528.vk.me/v410528521/3cc5/tshcI3KFsPs.jpg',
                'can_post'                  => 1,
                'can_write_private_message' => 1,
                'vk_id'                     => 685521,
            ],
        ];

        $this->mockReqApi = Mockery::mock(\App\Http\Controllers\ReqApiController::class)->makePartial();
        $this->mockReqApi->shouldReceive('getFriends')->once()->andReturn($friends);

        $this->mockUserRep = Mockery::mock(\App\Repositories\UserRepository::class)->makePartial();
        $this->mockUserRep->shouldReceive('getToken')->once()->andReturn('sadfasdfsdfsdf');
        $this->mockUserRep->shouldReceive('getUid')->once()->andReturn('123123123');

        $this->mockGratRep = Mockery::mock(\App\Repositories\GratterRepository::class)->makePartial();

        $this->class = new \App\Http\Controllers\BDayController(
            $this->mockReqApi,
            $this->mockUserRep,
            $this->mockGratRep
        );
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testFriendsForCongratulations()
    {
        $this->mockGratRep->shouldReceive('alreadyGratterOrFalse')->twice()->andReturn(false);

        $resp = $this->class->friendsForCongratulations();

        $this->assertCount(2, $resp);
    }

    public function testUpcomingBday()
    {
        $resp = $this->class->upcomingBday();
        $this->assertCount(2, $resp);

        $d1 = Carbon\Carbon::createFromFormat('d.m',$resp[0]['bdate']);
        $d2 = Carbon\Carbon::createFromFormat('d.m',$resp[1]['bdate']);
        $this->assertTrue($d1->lte($d2));
    }
}
