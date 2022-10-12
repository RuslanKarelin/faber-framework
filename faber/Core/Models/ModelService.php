<?php

namespace Faber\Core\Models;

use Faber\Core\Contracts\Database\Connection;
use Faber\Core\Contracts\Database\DBService;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Utils\Collection;

class ModelService implements DBService
{
    protected Connection $connection;
    protected Reflection $reflection;

    protected function getClassName(string $className): string
    {
        return  $className ?: $this->className;
    }

    public function __construct(protected string $className = '')
    {
        $container = Container::getInstance();
        $this->connection = $container->get(Connection::class);
        $this->reflection = $container->get(Reflection::class);
    }

    public function createItem(string $sqlQuery, string $className = ''): mixed
    {
        if ($rows = $this->connection->query($sqlQuery)) {
            $model = $this->reflection->createObject($this->getClassName($className));
            $model->setAttributes($rows[0]);
        }
        return $model ?? null;
    }

    public function createCollection(string $sqlQuery, string $className = ''): Collection
    {
        $collection = new Collection();
        if ($rows = $this->connection->query($sqlQuery)) {
            foreach ($rows as $row) {
                $model = $this->reflection->createObject($this->getClassName($className));
                $model->setAttributes($row);
                $collection->push($model);
            }
        }
        return $collection;
    }

    public function query(string $sqlQuery): array|null
    {
        return $this->connection->query($sqlQuery);
    }

    public function create(string $sqlQuery, array $data): int
    {
        return $this->connection->create($sqlQuery, $data);
    }

    public function insert(string $sqlQuery, array $data): bool
    {
        $this->connection->insert($sqlQuery, $data);
        return true;
    }

    public function update(string $sqlQuery, array $data, mixed $object = null): bool
    {
        $this->connection->update($sqlQuery, $data);
        return true;
    }

    public function destroy(string $sqlQuery, mixed $object = null): bool
    {
        $this->connection->destroy($sqlQuery);
        return true;
    }
}