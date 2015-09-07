<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var bool
     */
    private $useTestDb = true;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        if ($this->useTestDb) {
            putenv('DB_CONNECTION=testing');
            Artisan::call('migrate:refresh');
        }
    }

    /**
     * @param boolean $useTestDb
     */
    public function setUseTestDb($useTestDb)
    {
        $this->useTestDb = $useTestDb;
    }
}
