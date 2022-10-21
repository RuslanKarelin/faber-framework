<?php

namespace Faber\Core\Database\Migrations;

use Faber\Core\Contracts\Database\Connection;
use Faber\Core\Contracts\Database\Migrations\MigrationService as IMigrationService;
use Faber\Core\DI\Container;

class MigrationService implements IMigrationService
{
    protected Connection $connection;

    public function __construct()
    {
        $this->connection = Container::getInstance()->get(Connection::class);
    }

    public function query(string $query): array|null
    {
        return $this->connection->query($query);
    }

    /**
     * @throws \Faber\Core\Exceptions\DBException
     */
    public function exec(string $query): void
    {
        $this->connection->exec($query);
    }
}