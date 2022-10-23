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

    public function dropColumn(string $table, string|array $column): void
    {
        $this->builder->table($table);
        $this->builder->dropColumn($column);
        $this->builder->updateTable();
    }

    public function dropForeign(string $table, string $index): void
    {
        $this->builder->table($table);
        $this->builder->dropForeign($index);
        $this->builder->updateTable();
    }

    public function dropPrimary(string $table, string $index): void
    {
        $this->builder->table($table);
        $this->builder->dropPrimary($index);
        $this->builder->updateTable();
    }

    public function dropUnique(string $table, string $index): void
    {
        $this->builder->table($table);
        $this->builder->dropUnique($index);
        $this->builder->updateTable();
    }

    public function dropIndex(string $table, string $index): void
    {
        $this->builder->table($table);
        $this->builder->dropUnique($index);
        $this->builder->updateTable();
    }

    public function dropFullText(string $table, string $index): void
    {
        $this->builder->table($table);
        $this->builder->dropFullText($index);
        $this->builder->updateTable();
    }

    public function dropSpatialIndex(string $table, string $index): void
    {
        $this->builder->table($table);
        $this->builder->dropSpatialIndex($index);
        $this->builder->updateTable();
    }
}