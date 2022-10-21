<?php

namespace Faber\Core\Database\Migrations\Builder;

use Faber\Core\Database\Migrations\Builder\MysqlBuilder;

class BuilderFactory
{
    public static function getBuilderClass(string $DBMS): string
    {
        return match ($DBMS) {
            default => MysqlBuilder::class,
        };
    }
}