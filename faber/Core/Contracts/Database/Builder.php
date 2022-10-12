<?php

namespace Faber\Core\Contracts\Database;

use Faber\Core\Utils\Collection;

interface Builder
{
    public function table(string $tableName): static;
    public function select(...$args): static;
    public function where(string $column, string $operator, string|int|bool $value): static;
    public function orWhere(string $column, string $operator, string|int|bool|null $value = null): static;
    public function whereIn(string $column, array $values = []): static;
    public function whereNotIn(string $column, array $values = []): static;
    public function whereBetween(string $column, int|string $left, int|string $right): static;
    public function whereIsNull(string $column): static;
    public function whereIsNotNull(string $column): static;
    public function orderBy(string $column, string $direction = 'asc'): static;
    public function limit(int $value): static;
    public function offset(int $value): static;
    public function join(string $tableName, string $field1, string $operator, string $field2): static;
    public function leftJoin(string $tableName, string $field1, string $operator, string $field2): static;
    public function rightJoin(string $tableName, string $field1, string $operator, string $field2): static;
    public function fullJoin(string $tableName, string $field1, string $operator, string $field2): static;
    public function crossJoin(string $tableName): static;
    public function union(string $tableName): static;
    public function groupBy(string $field): static;
    public function having(string|int $value1, string $operator, string|int $value2): static;
    public function count(string $column = '*'): int;
    public function get(): Collection;
    public function toSql(): string;
    public function setClassName(string $className): static;
    public function setDBService(DBService $dbService): static;
    public function setPaginator(Paginator $paginator): static;
    public function create(array $data): mixed;
    public function insert(array $data): bool;
    public function update(array $data): bool;
    public function destroy(): bool;
    public function find(int $id): mixed;
    public function paginate(?int $perPage = null): Paginator;
}