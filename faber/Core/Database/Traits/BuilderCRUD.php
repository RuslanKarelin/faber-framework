<?php

namespace Faber\Core\Database\Traits;

trait BuilderCRUD
{
    public function create(array $data): mixed
    {
        $data = $this->createStubModel()->getFillableData($data);
        $fields = array_keys($data);
        $placeholders = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $this->sqlQuery = 'insert into `' . $this->tableName . '` ('
            . implode(', ', $fields) . ') values ('
            . implode(', ', $placeholders) . ')';
        $id = $this->dbService->create($this->sqlQuery, $data);
        return $this->find($id);;
    }

    public function insert(array $data): bool
    {
        if (empty($data)) throw new \Exception('$data cannot be empty');

        $stubModel = $this->createStubModel();
        foreach ($data as $key =>  $row) {
            $data[$key] = $stubModel->getFillableData($row);
        }

        $item = $data[0];
        $fields = array_keys($item);
        $placeholders = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $this->sqlQuery = 'insert into `' . $this->tableName . '` ('
            . implode(', ', $fields) . ') values ('
            . implode(', ', $placeholders) . ')';
        return $this->dbService->insert($this->sqlQuery, $data);
    }

    public function update(array $data, mixed $object = null): bool
    {
        //$data = $this->createStubModel()->getFillableData($data);
        $placeholders = array_map(function ($field) {
            return $field . '=:' . $field;
        }, array_keys($data));
        $this->update = 'update `' . $this->tableName . '` set '
            . implode(', ', $placeholders);
        $this->prepareQueryString();
        return $this->dbService->update($this->toSql(), $data, $object);
    }

    public function destroy(mixed $object = null): bool
    {
        if (is_int($object)) {
            $this->where($this->createStubModel()->getKeyName(), $object);
            $object = null;
        }
        if (is_array($object)) {
            $this->whereIn($this->createStubModel()->getKeyName(), $object);
            $object = null;
        }
        $this->delete = 'delete from `' . $this->tableName . '`';
        $this->prepareQueryString();
        return $this->dbService->destroy($this->toSql(), $object);
    }
}