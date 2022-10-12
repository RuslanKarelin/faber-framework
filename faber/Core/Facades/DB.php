<?php

namespace Faber\Core\Facades;

use Faber\Core\Contracts\Database\Builder;
use Faber\Core\Contracts\Database\DBService;
use Faber\Core\Contracts\Database\Paginator;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Models\ModelService;
use Faber\Core\Utils\Collection;

/**
 * @method static Builder table(string $tableName);
 * @method static Builder select(...$args);
 * @method static Builder where(string $column, string $operator, string|int|bool $value)
 * @method static Builder orWhere(string $column, string $operator, string|int|bool|null $value = null)
 * @method static Builder whereIn(string $column, array $values = [])
 * @method static Builder whereNotIn(string $column, array $values = [])
 * @method static Builder whereBetween(string $column, int|string $left, int|string $right)
 * @method static Builder whereIsNull(string $column)
 * @method static Builder whereIsNotNull(string $column)
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder limit(int $value)
 * @method static Builder offset(int $value)
 * @method static Builder join(string $tableName, string $field1, string $operator, string $field2)
 * @method static Builder leftJoin(string $tableName, string $field1, string $operator, string $field2)
 * @method static Builder rightJoin(string $tableName, string $field1, string $operator, string $field2)
 * @method static Builder fullJoin(string $tableName, string $field1, string $operator, string $field2)
 * @method static Builder crossJoin(string $tableName)
 * @method static Builder union(string $tableName)
 * @method static Builder groupBy(string $field)
 * @method static Builder having(string|int $value1, string $operator, string|int $value2)
 * @method static int count(string $column = '*');
 * @method static Collection get();
 * @method static string toSql();
 * @method static Builder setClassName(string $className)
 * @method static Builder setDBService(DBService $dbService)
 * @method static Builder setPaginator(Paginator $paginator)
 * @method static mixed create(array $data): mixed;
 * @method static bool insert(array $data)
 * @method static bool update(array $data)
 * @method static bool destroy()
 * @method static mixed find(int $id)
 * @method static Paginator paginate(?int $perPage = null)
 *
 * @see Builder
 */
class DB extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "DB";
    }

    protected static function prepareInstance(mixed $instance): void
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        $instance->setDBService(new ModelService(''))
            ->setPaginator($reflection->createObject(Paginator::class));
    }
}