<?php

class GratterRepositoryTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var \App\Repositories\GratterRepository
     */
    private $class;

    /**
     * @var App\User
     */
    private $user;

    public function setUp()
    {
        parent::setUseTestDb(true);
        parent::setUp();

        $this->class = new \App\Repositories\GratterRepository();

        factory(App\User::class, 1)->create();
        factory(App\Message::class, 2)->create();
        factory(App\Gratter::class, 50)->create();

        $this->user = App\User::whereId(1)->first();

        $this->be($this->user);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLatestGratters()
    {
        $result = $this->class->latestGratters();

        $this->seeInDatabase('gratters', ['id' => $result[0]['id']]);
        $this->seeInDatabase('gratters', ['id' => $result[1]['id']]);
        $this->seeInDatabase('gratters', ['id' => $result[2]['id']]);
        $this->assertGreaterThan($result[1]['id'], $result[0]['id']);
        $this->assertGreaterThan($result[2]['id'], $result[1]['id']);
    }

    public function testAlreadyGratterOrFalse()
    {
        $uid1 = App\Gratter::whereUserId($this->user->id)->where('year', '=', date('Y'))->first()->to;
        $res1 = $this->class->alreadyGratterOrFalse($uid1);
        $this->assertTrue($res1);

        do {
            $fakeUid = mt_rand(10000, 999999);
        } while (App\Gratter::whereTo($fakeUid)
                ->whereUserId($this->user->id)
                ->where('year', '=', date('Y'))
                ->first() !== null);
        $res2 = $this->class->alreadyGratterOrFalse($fakeUid);
        $this->assertFalse($res2);
    }

    public function testAddSendedGratters()
    {
        $msgId = App\Message::whereUserId($this->user->id)->first()->id;
        do {
            $fakeUid = mt_rand(10000, 999999);
        } while (App\Gratter::whereTo($fakeUid)
                ->whereUserId($this->user->id)
                ->where('year', '=', date('Y'))
                ->first() !== null);
        $this->class->addSendedGratters($fakeUid, $msgId);
        $this->seeInDatabase('gratters', ['to' => $fakeUid, 'message_id' => $msgId, 'year' => date('Y')]);
    }

    public function testGratterMessageId()
    {
        $uid = App\Gratter::whereUserId($this->user->id)->where('year', '=', date('Y'))->first();
        $resp = $this->class->gratterMessageId($uid->to);
        $this->assertEquals($resp, $uid->message_id);
    }
}
