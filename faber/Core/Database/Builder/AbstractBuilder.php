<?php

namespace Faber\Core\Database\Builder;

use Faber\Core\Contracts\Database\Builder;
use Faber\Core\Contracts\Database\DBService;
use Faber\Core\Contracts\Database\Paginator;
use Faber\Core\Database\Traits\BuilderCRUD;
use Faber\Core\Database\Traits\BuilderRelation;
use Faber\Core\Database\Traits\BuilderSelect;
use Faber\Core\Helpers\DB;
use Faber\Core\Models\Model;

abstract class AbstractBuilder implements Builder
{
    use BuilderSelect, BuilderCRUD, BuilderRelation;

    protected DBService $dbService;
    protected Paginator $paginator;
    protected string $className = '';
    protected string $tableName = '';

    public function setClassName(string $className): static
    {
        $this->className = $className;
        return $this;
    }

    public function setDBService(DBService $dbService): static
    {
        $this->dbService = $dbService;
        return $this;
    }

    public function setPaginator(Paginator $paginator): static
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function table(string $tableName): static
    {
        $this->className = DB::getClassNameFromTableName($this->tableName = $tableName);
        return $this;
    }

    public function toSql(): string
    {
        return $this->sqlQuery;
    }

    public function createStubModel(): Model
    {
        return new $this->className();
    }
}