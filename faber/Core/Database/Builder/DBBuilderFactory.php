<?php

namespace Faber\Core\Database\Builder;

use Faber\Core\Database\Builder\MysqlBuilder;

class DBBuilderFactory
{
    public static function getBuilderClass(string $DBMS): string
    {
        return match ($DBMS) {
            default => MysqlBuilder::class,
        };
    }
}