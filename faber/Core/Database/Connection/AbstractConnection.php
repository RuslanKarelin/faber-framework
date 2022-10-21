<?php

namespace Faber\Core\Database\Connection;

use PDO;
use Faber\Core\Contracts\Database\Connection;
use Faber\Core\Exceptions\DBException;

abstract class AbstractConnection implements Connection
{
    protected ?PDO $PDO = null;

    abstract public function connection();

    public function close()
    {
        $this->PDO = null;
    }

    public function exec(string $query): void
    {
        try {
            $this->PDO->exec($query);
        } catch (\Exception $exception) {
            throw new DBException($exception, $exception->getCode());
        }
    }

    public function query(string $query): array|null
    {
        $STH = $this->PDO->query($query);
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        return $STH->fetchAll();
    }

    public function create(string $sqlQuery, array $data): int
    {
        try {
            $this->PDO->beginTransaction();
            $this->PDO->prepare($sqlQuery)->execute($data);
            $id = $this->PDO->lastInsertId();
            $this->PDO->commit();
        } catch (\Exception $exception) {
            $this->PDO->rollback();
            throw new DBException($exception, $exception->getCode());
        }
        return $id;
    }

    public function insert(string $sqlQuery, array $data): void
    {
        try {
            $this->PDO->beginTransaction();
            $stmt = $this->PDO->prepare($sqlQuery);
            foreach ($data as $row) {
                $stmt->execute($row);
            }
            $this->PDO->commit();
        } catch (\Exception $exception) {
            $this->PDO->rollback();
            throw new DBException($exception, $exception->getCode());
        }
    }

    public function update(string $sqlQuery, array $data): void
    {
        try {
            $this->PDO->beginTransaction();
            $this->PDO->prepare($sqlQuery)->execute($data);
            $this->PDO->commit();
        } catch (\Exception $exception) {
            $this->PDO->rollback();
            throw new DBException($exception, $exception->getCode());
        }
    }

    public function destroy(string $query): void
    {
        try {
            $this->PDO->query($query);
        } catch (\Exception $exception) {
            throw new DBException($exception, $exception->getCode());
        }
    }
}