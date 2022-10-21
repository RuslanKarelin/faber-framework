<?php

namespace Faber\Core\Database\Migrations;

use Faber\Core\Contracts\Database\Migrations\Builder;
use Faber\Core\Exceptions\DBException;
use Faber\Core\Facades\DB;

class Schema
{
    public function __construct(protected Builder $builder)
    {
    }

    /**
     * @throws DBException
     */
    public function create(string $table, \Closure $callback): void
    {
        if (DB::tableExists($table)) throw new DBException("Table {$table} exists");
        $this->builder->table($table);
        $callback($this->builder);
        $this->builder->createTable();
    }

    /**
     * @throws DBException
     */
    public function table(string $table, \Closure $callback): void
    {
        if (!DB::tableExists($table)) throw new DBException("Table {$table} not exists");
        $this->builder->table($table);
        $callback($this->builder);
        $this->builder->updateTable();
    }

    public function dropIfExists(string $table): void
    {
        if (DB::tableExists($table)) {
            DB::dropTable($table);
        }
    }
}