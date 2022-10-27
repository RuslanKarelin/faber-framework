<?php

namespace Faber\Core\Models;

use Faber\Core\Contracts\Database\Builder;
use Faber\Core\Contracts\Database\DBService;
use Faber\Core\Contracts\Database\Paginator;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Helpers\DB;
use Faber\Core\Models\Relations\BelongsTo;
use Faber\Core\Models\Relations\HasMany;
use Faber\Core\Models\Relations\HasOne;
use Faber\Core\Models\Relations\Relation;
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
 * @method static int count(string $column = '*')
 * @method static Builder with(array $relations)
 * @method static Collection get();
 * @method static string toSql();
 * @method static Builder setClassName(string $className)
 * @method static Builder setDBService(DBService $dbService)
 * @method static Builder setPaginator(Paginator $paginator)
 * @method static mixed create(array $data): mixed;
 * @method static bool insert(array $data)
 * @method static bool update(array $data)
 * @method static bool destroy(mixed $data = null)
 * @method static static|null find(int|string $id)
 * @method static Paginator paginate(?int $perPage = null)
 *
 * @see Builder
 */
abstract class Model
{
    protected static string $tableName = '';
    protected array $attributes = [];
    protected string $primaryKey = 'id';
    protected static array $allowedMethodsForModel = ['update', 'destroy'];
    protected array $fillable = [];
    public array $relations = [];
    public static $perPage = 15;

    public static function getBuilder(string $className): mixed
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        return $reflection->createObject(Builder::class)
            ->setDBService(new ModelService($className))
            ->setPaginator($reflection->createObject(Paginator::class))
            ->setClassName($className)
            ->table($className::$tableName ?: DB::getTableNameFromClassName($className));
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function __get(string $name): mixed
    {
        if (array_key_exists($name, $this->relations)) {
            return $this->relations[$name];
        }

        if (method_exists(static::class, $name)) {
            $reflection = Container::getInstance()->get(Reflection::class);
            $result = $reflection->handleMethod($this, $name);
            if ($result instanceof Relation) {
                $relation = $result->getRelation();
                $this->relations[$name] = $relation;
                return $relation;
            }
            return $result;
        }

        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return null;
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        $className = static::class;
        if (!method_exists($className, $name)) {
            return static::getBuilder($className)->{$name}(...$arguments);
        }
        return $className::{$name}(...$arguments);
    }

    public function __call(string $name, array $arguments)
    {
        $className = $this::class;
        if (!method_exists($this, $name) && in_array($name, static::$allowedMethodsForModel)) {
            $arguments[] = $this;
            $keyName = $this->getKeyName();
            return static::getBuilder($className)->where($keyName, $this->{$keyName})->{$name}(...$arguments);
        }
        return $this->{$name}(...$arguments);
    }

    public function getFillableData(array $data): array
    {
        $newData = [];
        foreach ($this->fillable as $fieldName) {
            if (isset($data[$fieldName])) {
                $newData[$fieldName] = $data[$fieldName];
            }
        }
        return $newData;
    }

    public function getForeignKey(): string
    {
        return DB::getTableNameFromClassName(static::class, true) . '_' . $this->getKeyName();
    }

    public function getKeyName(): string
    {
        return $this->primaryKey;
    }

    public function hasOne(string $related, ?string $foreignKey = null, ?string $localKey = null): mixed
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->getKeyName();
        return new HasOne($this->getBuilder($related), $this, $related, $foreignKey, $localKey);
    }

    public function belongsTo(string $related, ?string $foreignKey = null, ?string $ownerKey = null): mixed
    {
        $relatedInstance = new $related;
        $foreignKey = $foreignKey ?: $relatedInstance->getForeignKey();
        $ownerKey = $ownerKey ?: $relatedInstance->getKeyName();
        return new BelongsTo($this->getBuilder($related), $this, $related, $foreignKey, $ownerKey);
    }

    public function hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null): mixed
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->getKeyName();
        return new HasMany($this->getBuilder($related), $this, $related, $foreignKey, $localKey);
    }

    public function load(array $relations): static
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        $this->relations = $reflection->createObject(RelationLoader::class)->load($relations, static::class, $this);
        return $this;
    }
}