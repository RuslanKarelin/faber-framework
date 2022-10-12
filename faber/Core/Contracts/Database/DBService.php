<?php

namespace Faber\Core\Contracts\Database;

use Faber\Core\Utils\Collection;

interface DBService
{
    public function createItem(string $sqlQuery, string $className = ''): mixed;
    public function createCollection(string $sqlQuery, string $className = ''): Collection;
    public function query(string $sqlQuery): array|null;
    public function create(string $sqlQuery, array $data): int;
    public function insert(string $sqlQuery, array $data): bool;
    public function update(string $sqlQuery, array $data, mixed $object = null): bool;
    public function destroy(string $sqlQuery, mixed $object = null): bool;
}