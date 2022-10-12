<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Database\Connection;
use Faber\Core\Database\DBConnectionFactory;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $dbConnection = DBConnectionFactory::create(config('database.default'));
        $this->container->bind(Connection::class, $dbConnection::class);
        $dbConnection->connection();
        $this->container->singleton(Connection::class, $dbConnection);
    }
}