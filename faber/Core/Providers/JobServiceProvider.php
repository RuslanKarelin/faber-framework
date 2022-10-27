<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Jobs\Connection;
use Faber\Core\Jobs\ConnectionFactory;

class JobServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $connection = ConnectionFactory::create(config('queue.default'));
        $this->container->bind(Connection::class, $connection::class);
        $this->container->singleton(Connection::class, $connection);
    }
}