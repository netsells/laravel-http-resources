<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Illuminate\Database\Connection;
use Netsells\Http\Resources\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->withFactories(__DIR__ . '/Database/Factories');
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * @param array $queryLog
     * @param callable $fn
     * @return mixed
     */
    protected function withQueryLog(&$queryLog, callable $fn)
    {
        /** @var Connection $connection */
        $connection = $this->app->get(Connection::class);
        $connection->enableQueryLog();
        $connection->flushQueryLog();

        return tap($fn(), function () use ($connection, &$queryLog) {
            $queryLog = $connection->getQueryLog();
            $connection->disableQueryLog();
        });
    }
}
