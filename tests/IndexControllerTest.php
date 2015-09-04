<?php

class IndexControllerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var \Mockery\Mock
     */
    private $mockReqApi;
    /**
     * @var \Mockery\Mock
     */
    private $mockMsgRep;
    /**
     * @var \Mockery\Mock
     */
    private $mockGratRep;
    /**
     * @var \Mockery\Mock
     */
    private $mockBDay;
    /**
     * @var App\Http\Controllers\IndexController
     */
    private $class;
    /**
     * @var \Mockery\Mock
     */
    private $mockEvent;

    public function setUp()
    {
        parent::setUp();

        factory(App\User::class,3)->create();
        $this->be(App\User::all()->first());

        $this->mockReqApi = Mockery::mock(\App\Http\Controllers\ReqApiController::class)->makePartial();
        $this->mockMsgRep = Mockery::mock(\App\Repositories\MessageRepository::class)->makePartial();
        $this->mockGratRep = Mockery::mock(\App\Repositories\GratterRepository::class)->makePartial();
        $this->mockBDay = Mockery::mock(\App\Http\Controllers\BDayController::class)->makePartial();

        $this->app->instance('App\Http\Controllers\ReqApiController', $this->mockReqApi);
        $this->app->instance('App\Repositories\GratterRepository', $this->mockGratRep);
        $this->app->instance('App\Http\Controllers\BDayController', $this->mockBDay);
        $this->app->instance('App\Repositories\MessageRepository', $this->mockMsgRep);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testIndex()
    {
        $friendsForCongrats = [
            0 => [
                'first_name'                => 'Оксана',
                'last_name'                 => 'Гуда',
                'bdate'                     => '01.09.1986',
                'photo_100'                 => 'http://cs629424.vk.me/v629424086/80c2/qi-TrhMdPK0.jpg',
                'can_post'                  => 1,
                'can_write_private_message' => 1,
                'vk_id'                     => 161086,
            ],
            1 => [
                'first_name'                => 'Андрей',
                'last_name'                 => 'Аасаметс',
                'bdate'                     => '01.9.0000',
                'photo_100'                 => 'http://cs629300.vk.me/v629300927/1619/fIuUp8FPoPo.jpg',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'hidden'                    => 1,
                'vk_id'                     => 337927,
            ],
        ];
        $latestCongrats = [
            1 => [
                'id'         => '13',
                'user_id'    => '1',
                'to'         => '337927',
                'message_id' => '4',
                'year'       => '2015',
                'created_at' => '2015-08-11 13:28:48',
                'updated_at' => '2015-08-11 13:28:48',
            ],
            2 => [
                'id'         => '14',
                'user_id'    => '1',
                'to'         => '161086',
                'message_id' => '5',
                'year'       => '2015',
                'created_at' => '2015-08-29 11:13:22',
                'updated_at' => '2015-08-29 11:13:22',
            ],
        ];
        $nameAndAvatar = [
            'avatar' => 'https://pp.vk.me/c627829/v627829149/11748/W9B6fpzHZEQ.jpg',
            'name'   => 'Ирина Линдерман',
        ];
        $message = 'C ДР!';
        $upcomingBDay = [
            0 => [
                'bdate'                     => '02.09',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'vk_id'                     => 5895718,
                'name'                      => 'Яна Евдокимова',
                'avatar'                    => 'http://cs624819.vk.me/v624819718/22035/clUOzDTvYj0.jpg',
            ],
            1 => [
                'bdate'                     => '07.09',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'vk_id'                     => 4661749,
                'name'                      => 'Михаил Савченко',
                'avatar'                    => 'http://cs323726.vk.me/v323726749/95fd/XQEbM_mbmBU.jpg',
            ],
            2 => [
                'bdate'                     => '08.09',
                'can_post'                  => 0,
                'can_write_private_message' => 1,
                'vk_id'                     => 7764505,
                'name'                      => 'Анна Ракович',
                'avatar'                    => 'http://cs623720.vk.me/v623720505/450ba/MggG1Q8fYUc.jpg',
            ],
        ];

        $this->mockBDay->shouldReceive('upcomingBday')->withNoArgs()->once()->andReturn($upcomingBDay);
        $this->mockGratRep->shouldReceive('latestGratters')->withNoArgs()->once()->andReturn($latestCongrats);
        $this->mockReqApi->shouldReceive('fetchNameAndAvatar')->withAnyArgs()->twice()->andReturn($nameAndAvatar);
        $this->mockMsgRep->shouldReceive('getMessageTextById')->withAnyArgs()->twice()->andReturn($message);

        Event::shouldReceive('fire')->zeroOrMoreTimes();
        $this->action('GET', 'IndexController@index');
        $this->assertViewHas(['upcoming', 'latest']);
    }
}
