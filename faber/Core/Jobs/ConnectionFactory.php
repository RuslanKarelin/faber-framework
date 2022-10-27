<?php

namespace Faber\Core\Jobs;

use Faber\Core\Contracts\Jobs\Connection;
use Faber\Core\Jobs\Connections\Database;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;

class ConnectionFactory
{
    public static function create(string $DBMS): Connection
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        return $reflection->createObject(match ($DBMS) {
            default => Database::class,
        });
    }
}