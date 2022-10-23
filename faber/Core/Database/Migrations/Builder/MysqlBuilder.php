<?php

namespace Faber\Core\Database\Migrations\Builder;

use Faber\Core\Database\Migrations\Builder\AbstractBuilder;
use Faber\Core\Database\Migrations\Builder\Columns\IntegerColumn;
use Faber\Core\Database\Migrations\Builder\Columns\StringColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TextColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TimestampColumn;
use Faber\Core\Database\Migrations\Builder\Drop\DropColumn;
use Faber\Core\Database\Migrations\Builder\Drop\DropForeign;
use Faber\Core\Database\Migrations\Builder\Drop\DropFullText;
use Faber\Core\Database\Migrations\Builder\Drop\DropIndex;
use Faber\Core\Database\Migrations\Builder\Drop\DropPrimary;
use Faber\Core\Database\Migrations\Builder\Drop\DropSpatialIndex;
use Faber\Core\Database\Migrations\Builder\Drop\DropUnique;
use Faber\Core\Database\Migrations\Builder\Foreign\Foreign;
use Faber\Core\Database\Migrations\Builder\Index\FullText;
use Faber\Core\Database\Migrations\Builder\Index\Index;
use Faber\Core\Database\Migrations\Builder\Index\Primary;
use Faber\Core\Database\Migrations\Builder\Index\SpatialIndex;
use Faber\Core\Database\Migrations\Builder\Index\Unique;

class MysqlBuilder extends AbstractBuilder
{
    protected function prepareQuery(): void
    {
        $prefix = $this->isUpdate ? 'ADD ' : '';
        $this->setColumns($prefix);
        $this->setDropColumns();
        $this->setIndexes($prefix);
        $this->setDropIndexes();
    }

    protected function setColumns(string $prefix): void
    {
        if ($this->string) {
            foreach ($this->string as $stringColumn) {
                $this->query .= $prefix . $this->getStringColumn($stringColumn);
                if (method_exists($stringColumn, 'getUnique')) {
                    if ($stringColumn->getUnique()) {
                        $this->query .= $prefix . $this->setUnique($stringColumn);
                    }
                }
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


    protected function setIndexes(string $prefix): void
    {
        if ($this->foreign) {
            foreach ($this->foreign as $foreign) {
                $this->query .= $prefix . $this->getForeign($foreign);
            }
        }

        if ($this->primary) {
            foreach ($this->primary as $primary) {
                $this->query .= $this->getPrimary($primary);
            }
        }

        if ($this->unique) {
            foreach ($this->unique as $unique) {
                $this->query .= $this->getUnique($unique);
            }
        }

        if ($this->fullText) {
            foreach ($this->fullText as $fullText) {
                $this->query .= $this->getFullText($fullText);
            }
        }

        if ($this->index) {
            foreach ($this->index as $index) {
                $this->query .= $this->getIndex($index);
            }
        }

        if ($this->spatialIndex) {
            foreach ($this->spatialIndex as $spatialIndex) {
                $this->query .= $this->getSpatialIndex($spatialIndex);
            }
        }
    }

    protected function setDropIndexes(): void
    {
        if ($this->dropForeign) {
            foreach ($this->dropForeign as $dropForeign) {
                $this->query .= $this->getDropForeign($dropForeign);
            }
        }

        if ($this->dropPrimary) {
            foreach ($this->dropPrimary as $dropPrimary) {
                $this->query .= $this->getDropPrimary($dropPrimary);
            }
        }

        if ($this->dropUnique) {
            foreach ($this->dropUnique as $dropUnique) {
                $this->query .= $this->getDropUnique($dropUnique);
            }
        }

        if ($this->dropFullText) {
            foreach ($this->dropFullText as $dropFullText) {
                $this->query .= $this->getDropFullText($dropFullText);
            }
        }

        if ($this->dropIndex) {
            foreach ($this->dropIndex as $dropIndex) {
                $this->query .= $this->getDropIndex($dropIndex);
            }
        }

        if ($this->dropSpatialIndex) {
            foreach ($this->dropSpatialIndex as $dropSpatialIndex) {
                $this->query .= $this->getDropSpatialIndex($dropSpatialIndex);
            }
        }
    }

    protected function setDropColumns(): void
    {
        if ($this->dropColumn) {
            foreach ($this->dropColumn as $dropColumn) {
                $this->query .= $this->getDropColumn($dropColumn);
            }
        }
    }

    protected function setUnique($column): string
    {
        $columnName = $column->getColumn();
        return "UNIQUE `{$columnName}` (`{$columnName}`), " . PHP_EOL;
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

    protected function getForeign(Foreign $foreign): string
    {
        $query = "foreign key (`{$foreign->getColumn()}`) references `{$foreign->getOn()}` (`{$foreign->getReferences()}`) ";
        if ($foreign->getOnDelete()) {
            $query .= "on delete {$foreign->getOnDelete()}";
        }
        return trim($query) . "," . PHP_EOL;
    }

    protected function getDropColumn(DropColumn $column): string
    {
        $query = "DROP `{$column->getColumn()}` ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getPrimary(Primary $index): string
    {
        $query = "ADD PRIMARY `{$index->getIndex()}` (`{$index->getIndex()}`) ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getUnique(Unique $index): string
    {
        $query = "ADD UNIQUE `{$index->getIndex()}` (`{$index->getIndex()}`) ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getIndex(Index $index): string
    {
        $query = "ADD INDEX `{$index->getIndex()}` (`{$index->getIndex()}`) ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getFullText(FullText $index): string
    {
        $query = "ADD FULLTEXT `{$index->getIndex()}` (`{$index->getIndex()}`) ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getSpatialIndex(SpatialIndex $index): string
    {
        $query = "ADD SPATIAL `{$index->getIndex()}` (`{$index->getIndex()}`) ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getDropPrimary(DropPrimary $index): string
    {
        return $this->getDropIndex($index);
    }

    protected function getDropForeign(DropForeign $index): string
    {
        $query = "DROP FOREIGN KEY `{$index->getIndex()}` ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getDropUnique(DropUnique $index): string
    {
        return $this->getDropIndex($index);
    }

    protected function getDropIndex(DropIndex $index): string
    {
        $query = "DROP INDEX `{$index->getIndex()}` ";
        return trim($query) . "," . PHP_EOL;
    }

    protected function getDropFullText(DropFullText $index): string
    {
        return $this->getDropIndex($index);
    }

    protected function getDropSpatialIndex(DropSpatialIndex $index): string
    {
        return $this->getDropIndex($index);
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
                if (!is_string($default)) $default = '"' . $default . '"';
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