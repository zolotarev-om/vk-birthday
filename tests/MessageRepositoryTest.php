<?php

class MessageRepositoryTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var App\User
     */
    private $user;
    /**
     * @var \App\Repositories\MessageRepository
     */
    private $class;

    public function setUp()
    {
        parent::setUseTestDb(true);

        parent::setUp();

        factory(App\User::class, 1)->create();
        factory(App\Message::class, 2)->create();

        $this->user = App\User::all()->first();
        $this->be($this->user);

        $this->class = new \App\Repositories\MessageRepository();
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testCreateMessageIfNotExist()
    {
        $existMessage = App\Message::whereUserId($this->user->id)->first();
        $resp1 = $this->class->createMessageIfNotExist($existMessage->text);
        $this->assertFalse($resp1);

        $newMessage = 'TestTestTest';
        $resp2 = $this->class->createMessageIfNotExist($newMessage);
        $this->assertTrue($resp2);
    }

    public function testDelMessageById()
    {
        $existMessage = App\Message::whereUserId($this->user->id)->first();
        $resp1 = $this->class->delMessageById($existMessage->id);
        $this->assertTrue($resp1);

        $newMessageId = 777;
        $resp2 = $this->class->delMessageById($newMessageId);
        $this->assertNull($resp2);
    }

    public function testGetMessageList()
    {
        $countMessage = App\Message::whereUserId($this->user->id)->count();
        $resp = $this->class->getMessageList();
        $this->assertEquals($countMessage, count($resp));
    }

    public function testGetMessageTextById()
    {
        $existMessage = App\Message::whereUserId($this->user->id)->first();
        $resp = $this->class->getMessageTextById($existMessage->id);
        $this->assertEquals($existMessage->text, $resp);
    }

    public function testGetRandomMessage()
    {
        $resp = $this->class->getRandomMessage();
        $this->seeInDatabase('messages', ['id' => key($resp), 'text' => current($resp)]);
    }
}
