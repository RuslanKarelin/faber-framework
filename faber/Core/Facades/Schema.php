<?php

namespace Faber\Core\Facades;

use Faber\Core\Database\Migrations\Schema as DBSchema;

/**
 * @method static void create(string $table, \Closure $callback)
 * @method static void table(string $table, \Closure $callback)
 * @method static void dropIfExists(string $table)
 * @method static void dropColumn(string $table, string|array $column)
 * @method static void dropPrimary(string $table, string $index)
 * @method static void dropUnique(string $table, string $index)
 * @method static void dropIndex(string $table, string $index)
 * @method static void dropFullText(string $table, string $index)
 * @method static void dropSpatialIndex(string $table, string $index)
 * @method static void dropForeign(string|array $index)
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