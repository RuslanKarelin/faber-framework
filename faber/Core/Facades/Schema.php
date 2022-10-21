<?php

namespace Faber\Core\Facades;

use Faber\Core\Database\Migrations\Schema as DBSchema;

/**
 * @method static void create(string $table, \Closure $callback)
 * @method static void table(string $table, \Closure $callback)
 * @method static void dropIfExists(string $table)
 *
 * @see DBSchema
 */
class Schema extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Schema";
    }
}