<?php

namespace Faber\Core\Database;

use Faber\Core\Contracts\Database\Connection;
use Faber\Core\Database\Connection\MysqlConnection;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;

class DBConnectionFactory
{
    public static function create(string $DBMS): Connection
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        return $reflection->createObject(match ($DBMS) {
            default => MysqlConnection::class,
        });
    }
}