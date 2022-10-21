<?php

namespace Faber\Core\Database\Migrations\Builder;

use Faber\Core\Database\Migrations\Builder\AbstractBuilder;
use Faber\Core\Database\Migrations\Builder\Columns\IntegerColumn;
use Faber\Core\Database\Migrations\Builder\Columns\StringColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TextColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TimestampColumn;

class MysqlBuilder extends AbstractBuilder
{
    protected function prepareQuery(): void
    {
        $prefix = $this->isUpdate ? 'ADD ' : '';
        if ($this->string) {
            foreach ($this->string as $stringColumn) {
                $this->query .= $prefix . $this->getStringColumn($stringColumn);
            }
        }

        if ($this->integer) {
            foreach ($this->integer as $integerColumn) {
                $this->query .= $prefix . $this->getIntegerColumn($integerColumn);
            }
        }

        if ($this->text) {
            foreach ($this->text as $textColumn) {
                $this->query .= $prefix . $this->getTextColumn($textColumn);
            }
        }

        if ($this->timestamps) {
            foreach ($this->timestamps as $timestampColumn) {
                $this->query .= $prefix . $this->getTimestampColumn($timestampColumn);
            }
        }
    }

    protected function getIdColumn(): string
    {
        return $this->getIntegerColumn($this->id);
    }

    protected function getStringColumn(StringColumn $column): string
    {
        $query = "`{$column->getColumn()}` varchar({$column->getLength()}) ";
        $query = $this->generalMethods($column, $query);
        return trim($query) . "," . PHP_EOL;
    }

    protected function getIntegerColumn(IntegerColumn $column): string
    {
        $query = "`{$column->getColumn()}` int ";
        if ($column->isUnsigned()) $query .= "unsigned ";
        $query = $this->generalMethods($column, $query);
        if ($column->isAutoIncrement()) $query .= "AUTO_INCREMENT ";
        if ($column->isPrimaryKey()) $query .= "PRIMARY KEY ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getTextColumn(TextColumn $column): string
    {
        $query = "`{$column->getColumn()}` text ";
        $query = $this->generalMethods($column, $query);
        return trim($query) . "," . PHP_EOL;
    }

    protected function getTimestampColumn(TimestampColumn $column): string
    {
        $query = "`{$column->getColumn()}` timestamp ";
        $query = $this->generalMethods($column, $query);
        return trim($query) . "," . PHP_EOL;
    }

    protected function generalMethods(mixed $column, string $query): string
    {
        if (!$column->isNull()) {
            $query .= "NOT NULL ";
        } else {
            $query .= "NULL ";
        }

        if (method_exists($column, 'getDefault')) {
            $default = $column->getDefault();
            if (!is_null($default)) {
                if (!is_string($default)) $default = '"'. $default . '"';
                $query .= "DEFAULT {$default}";
            }
        }

        return $query;
    }

    /**
     * @return void
     * @throws \Faber\Core\Exceptions\DBException
     */
    protected function completionOfTheQueryStringFormation(): void
    {
        $this->prepareQuery();
        $this->query = rtrim($this->query, ',' . PHP_EOL) . PHP_EOL . ($this->isCreate ? ');' : ';');
        $this->migrationService->exec($this->query);
    }

    /**
     * @throws \Faber\Core\Exceptions\DBException
     */
    public function createTable(): void
    {
        $this->setIsCreate(true);
        $this->query = "CREATE TABLE `{$this->table}` (" . PHP_EOL;
        if ($this->id) $this->query .= $this->getIdColumn();
        $this->completionOfTheQueryStringFormation();
    }

    /**
     * @throws \Faber\Core\Exceptions\DBException
     */
    public function updateTable(): void
    {
        $this->setIsUpdate(true);
        $this->query = "ALTER TABLE `{$this->table}` " . PHP_EOL;
        $this->completionOfTheQueryStringFormation();
    }
}