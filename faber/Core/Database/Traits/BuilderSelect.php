<?php

namespace Faber\Core\Database\Traits;

use Faber\Core\Contracts\Database\Paginator;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Models\RelationLoader;
use Faber\Core\Utils\Collection;

trait BuilderSelect
{
    protected string $select = '';
    protected string $update = '';
    protected string $delete = '';
    protected string $joinPrefix = ' ';
    protected string $prefixWhere = ' where ';
    protected string $having = '';
    protected string $sqlQuery = '';
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $where = [];
    protected array $orWhere = [];
    protected array $whereIn = [];
    protected array $whereNotIn = [];
    protected array $whereBetween = [];
    protected array $whereIsNull = [];
    protected array $whereIsNotNull = [];
    protected array $orderBy = [];
    protected array $join = [];
    protected array $leftJoin = [];
    protected array $rightJoin = [];
    protected array $fullJoin = [];
    protected array $crossJoin = [];
    protected array $union = [];
    protected array $groupBy = [];
    protected array $joinMethods = [
        'join', 'leftJoin', 'rightJoin', 'fullJoin', 'crossJoin', 'union'
    ];
    protected array $whereMethods = [
        'whereIn', 'whereNotIn', 'whereBetween', 'whereIsNull', 'whereIsNotNull'
    ];
    protected array $excludeValuesToSelect = [
        '*'
    ];
    protected array $withRelations = [];

    protected function column(string $column): string
    {
        return '`' . $this->tableName . '`.' . '`' . $column . '`';
    }

    protected function trimSqlQuery()
    {
        $this->sqlQuery = trim($this->sqlQuery);
    }

    protected function prepareJoin(string $method)
    {
        if ($this->{$method}) {
            $this->trimSqlQuery();
            $this->sqlQuery .= $this->joinPrefix . implode(' ', $this->{$method});
        }
    }

    protected function prepareWhere(string $method)
    {
        if ($this->{$method}) {
            $this->trimSqlQuery();
            $this->sqlQuery .= $this->prefixWhere . implode(' and ', $this->{$method});
            $this->prefixWhere = ' and ';
        }
    }

    protected function escapingIsString(mixed $value): mixed
    {
        return is_string($value) ? "'{$value}'" : $value;
    }

    protected function escapingItems(array $data): array
    {
        return array_map(function ($item) {
            return $this->escapingIsString($item);
        }, $data);
    }

    protected function prepareQueryString()
    {
        if (!$this->select) {
            $this->select('*');
        }

        $this->sqlQuery = $this->select;

        if ($this->delete) {
            $this->select = '';
            $this->sqlQuery = $this->delete;
        }

        if ($this->update) {
            $this->select = '';
            $this->delete = '';
            $this->sqlQuery = $this->update;
        }

        foreach ($this->joinMethods as $joinMethod) {
            $this->prepareJoin($joinMethod);
        }

        if ($this->where) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' where ' . implode(' and ', $this->where);
            $this->prefixWhere = ' and ';
        }

        if ($this->orWhere) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' or ' . implode(' or ', $this->orWhere);
            $this->prefixWhere = ' and ';
        }

        foreach ($this->whereMethods as $whereMethod) {
            $this->prepareWhere($whereMethod);
        }

        if ($this->groupBy) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' group by ' . implode(', ', $this->groupBy);
        }

        if ($this->groupBy && $this->having) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' ' . $this->having;
        }

        if ($this->orderBy) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' order by ' . implode(', ', $this->orderBy);
        }

        if (!is_null($this->limit)) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' limit ' . $this->limit;
        }

        if (!is_null($this->limit) && !is_null($this->offset)) {
            $this->trimSqlQuery();
            $this->sqlQuery .= ' offset ' . $this->offset;
        }
    }

    protected function setRelations(Collection $collection): Collection
    {
        if ($this->withRelations) {
            $reflection = Container::getInstance()->get(Reflection::class);
            $collection = $reflection->createObject(RelationLoader::class)->with($this->withRelations, $collection, $this->className);
            $this->withRelations = [];
        }
        return $collection;
    }

    public function select(...$args): static
    {
        $args = array_map(function ($arg) {
            return (!in_array($arg, $this->excludeValuesToSelect)) ? $this->column($arg) : $arg;
        }, $args);

        $this->select = 'select ' . implode(', ', $args) . ' from ' . '`' . $this->tableName . '`' . ' ' . $this->sqlQuery;
        return $this;
    }

    public function where(string $column, string|int $operator, string|int|bool|null $value = null): static
    {
        if (!$value) {
            $this->where[] = $this->column($column) . ' = ' . $this->escapingIsString($operator);
        } else {
            $this->where[] = $this->column($column) . ' ' . $operator . ' ' . $this->escapingIsString($value);
        }
        return $this;
    }

    public function orWhere(string $column, string $operator, string|int|bool|null $value = null): static
    {
        if (!$value) {
            $this->orWhere[] = $this->column($column) . ' = ' . $this->escapingIsString($operator);
        } else {
            $this->orWhere[] = $this->column($column) . ' ' . $operator . ' ' . $this->escapingIsString($value);
        }
        return $this;
    }

    public function whereIsNull(string $column): static
    {
        $this->whereIsNull[] = $this->column($column) . ' is null';
        return $this;
    }

    public function whereIsNotNull(string $column): static
    {
        $this->whereIsNotNull[] = $this->column($column) . ' is not null';
        return $this;
    }

    public function whereIn(string $column, array $values = []): static
    {
        if ($values)
            $this->whereIn[] = $this->column($column) . ' in (' . implode(', ', $this->escapingItems($values)) . ')';
        return $this;
    }

    public function whereNotIn(string $column, array $values = []): static
    {
        if ($values)
            $this->whereNotIn[] = $this->column($column) . ' not in (' . implode(', ', $this->escapingItems($values)) . ')';
        return $this;
    }

    public function whereBetween(string $column, int|string $left, int|string $right): static
    {
        if ($left and $right)
            $this->whereBetween[] = $this->column($column) . ' between ' . $this->escapingIsString($left) . ' and ' . $this->escapingIsString($right);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orderBy[] = $this->column($column) . ' ' . $direction;
        return $this;
    }

    public function limit(int $value): static
    {
        $this->limit = $value;
        return $this;
    }

    public function offset(int $value): static
    {
        $this->offset = $value;
        return $this;
    }

    public function join(string $tableName, string $field1, string $operator, string $field2): static
    {
        $this->join[] = 'inner join `' . $tableName . '` on `' . $field1 . '` ' . $operator . ' `' . $field2 . '`';
        return $this;
    }

    public function leftJoin(string $tableName, string $field1, string $operator, string $field2): static
    {
        $this->leftJoin[] = 'left join `' . $tableName . '` on `' . $field1 . '` ' . $operator . ' `' . $field2 . '`';
        return $this;
    }

    public function rightJoin(string $tableName, string $field1, string $operator, string $field2): static
    {
        $this->rightJoin[] = 'right join `' . $tableName . '` on `' . $field1 . '` ' . $operator . ' `' . $field2 . '`';
        return $this;
    }

    public function fullJoin(string $tableName, string $field1, string $operator, string $field2): static
    {
        $this->fullJoin[] = 'full join `' . $tableName . '` on `' . $field1 . '` ' . $operator . ' `' . $field2 . '`';
        return $this;
    }

    public function crossJoin(string $tableName): static
    {
        $this->crossJoin[] = 'cross join `' . $tableName . '`';
        return $this;
    }

    public function union(string $tableName): static
    {
        $this->union[] = 'union `' . $tableName . '`';
        return $this;
    }

    public function groupBy(string $field): static
    {
        $this->groupBy[] = '`' . $field . '`';
        return $this;
    }

    public function having(string|int $value1, string $operator, string|int $value2): static
    {
        $this->having = 'having ' . $this->escapingIsString($value1) . ' ' . $operator . ' ' . $this->escapingIsString($value2);
        return $this;
    }

    public function count(string $column = '*'): int
    {
        $this->select = 'select count(' . $column . ') count from ' . '`' . $this->tableName . '`';
        $this->limit = null;
        $this->offset = null;
        $this->prepareQueryString();
        return $this->dbService->query($this->toSql())[0]['count'];
    }

    public function find(int|string $id): mixed
    {
        $this->select = 'select * from ' . '`' . $this->tableName . '`';
        $this->where($this->createStubModel()->getKeyName(), $id)->limit(1);
        $this->prepareQueryString();
        return $this->dbService->createItem($this->toSql(), $this->className);
    }

    public function with(array $relations): static
    {
        $this->withRelations = $relations;
        return $this;
    }

    public function get(): Collection
    {
        $this->prepareQueryString();
        $collection = $this->dbService->createCollection($this->toSql(), $this->className);
        return $this->setRelations($collection);
    }

    public function paginate(?int $perPage = null): Paginator
    {
        $perPage = $perPage ?: $this->className::$perPage;
        $this->limit($perPage)->offset($this->paginator->getOffset($perPage));
        $this->prepareQueryString();
        $collection = $this->dbService->createCollection($this->toSql());
        $collection = $this->setRelations($collection);
        return $this->paginator->get($collection, $this->count(), $perPage);
    }
}