<?php

/**
 * Class SettingControllerTest
 */
class SettingControllerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Illuminate\Foundation\Testing\WithoutMiddleware;

    /**
     * @var \Mockery\Mock
     */
    private $mockMsgRep;
    /**
     * @var \Mockery\Mock
     */
    private $mockUsrRep;

    public function setUp()
    {
        parent::setUp();

        $this->mockMsgRep = Mockery::mock(\App\Repositories\MessageRepository::class);
        $this->mockUsrRep = Mockery::mock(\App\Repositories\UserRepository::class);
        $this->app->instance('App\Repositories\MessageRepository', $this->mockMsgRep);
        $this->app->instance('App\Repositories\UserRepository', $this->mockUsrRep);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testIndex()
    {
        $this->mockMsgRep->shouldReceive('getMessageList')->withNoArgs()->once();
        $this->mockUsrRep->shouldReceive('getToken')->withNoArgs()->once();
        $this->action('GET', 'SettingController@index');
        $this->assertViewHasAll(['message','token']);
    }

    public function testAddMessage()
    {
        $this->mockMsgRep->shouldReceive('createMessageIfNotExist')->withAnyArgs()->once();
        $this->action('POST', 'SettingController@addMessage', [], ['message' => 'asdasdasd']);
        $this->assertRedirectedToRoute('setting', [], ['result']);
    }

    public function testAddEmptyMessage()
    {
        $this->action('POST', 'SettingController@addMessage');
        $this->assertRedirectedToRoute('setting');
    }

    public function testDeleteMessage()
    {
        $this->mockMsgRep->shouldReceive('delMessageById')->withAnyArgs()->once();
        $this->action('POST', 'SettingController@deleteMessage', [], ['6' => '1']);
        $this->assertRedirectedToRoute('setting', [], ['result']);
    }

    public function testDeleteEmptyMessage()
    {
        $this->action('POST', 'SettingController@deleteMessage');
        $this->assertRedirectedToRoute('setting');
    }

    public function testAddOrUpdateToken()
    {
        $this->mockUsrRep->shouldReceive('setOrUpdateToken')->withAnyArgs()->once();
        $this->action('POST', 'SettingController@updateToken', [], [
            'token' =>
                'abf9130ef7c5da15dd26551a4f198fd0dd36993cfdd214cdceff770da9912cme214db623647ecb912b52c',
        ]);
        $this->assertRedirectedToRoute('setting', [], ['result']);
    }

    public function testAddOrUpdateEmptyToken()
    {
        $this->action('POST', 'SettingController@updateToken');
        $this->assertRedirectedToRoute('setting');
    }
}
