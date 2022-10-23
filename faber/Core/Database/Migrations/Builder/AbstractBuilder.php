<?php

namespace Faber\Core\Database\Migrations\Builder;

use Faber\Core\Contracts\Database\Migrations\Builder;
use Faber\Core\Contracts\Database\Migrations\MigrationService;
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

abstract class AbstractBuilder implements Builder
{
    protected string $query = '';
    protected string $table;
    protected bool $isCreate = false;
    protected bool $isUpdate = false;
    protected ?IntegerColumn $id = null;
    protected array $string = [];
    protected array $integer = [];
    protected array $text = [];
    protected array $timestamps = [];
    protected array $foreign = [];
    protected array $primary = [];
    protected array $unique = [];
    protected array $index = [];
    protected array $fullText = [];
    protected array $spatialIndex = [];
    protected array $dropColumn = [];
    protected array $dropForeign = [];
    protected array $dropPrimary = [];
    protected array $dropUnique = [];
    protected array $dropIndex = [];
    protected array $dropFullText = [];
    protected array $dropSpatialIndex = [];

    abstract protected function getIdColumn(): string;

    abstract protected function getStringColumn(StringColumn $column): string;

    abstract protected function getIntegerColumn(IntegerColumn $column): string;

    abstract protected function getTextColumn(TextColumn $column): string;

    abstract protected function getTimestampColumn(TimestampColumn $column): string;

    abstract protected function getForeign(Foreign $foreign): string;

    abstract protected function getDropColumn(DropColumn $column): string;

    abstract protected function getPrimary(Primary $index): string;

    abstract protected function getUnique(Unique $index): string;

    abstract protected function getIndex(Index $index): string;

    abstract protected function getFullText(FullText $index): string;

    abstract protected function getSpatialIndex(SpatialIndex $index): string;

    abstract protected function getDropForeign(DropForeign $index): string;

    abstract protected function getDropPrimary(DropPrimary $index): string;

    abstract protected function getDropUnique(DropUnique $index): string;

    abstract protected function getDropIndex(DropIndex $index): string;

    abstract protected function getDropFullText(DropFullText $index): string;

    abstract protected function getDropSpatialIndex(DropSpatialIndex $index): string;

    abstract protected function setUnique($column): string;

    abstract public function createTable(): void;

    abstract public function updateTable(): void;

    protected function setIsCreate(bool $isCreate): void
    {
        $this->isCreate = $isCreate;
    }

    protected function setIsUpdate(bool $isUpdate): void
    {
        $this->isUpdate = $isUpdate;
    }

    public function __construct(protected MigrationService $migrationService)
    {
    }

    public function table(string $table): void
    {
        $this->table = $table;
    }

    public function string(string $column, int $length = 255): StringColumn
    {
        return $this->string[] = new StringColumn($column, $length);
    }

    public function integer(string $column): IntegerColumn
    {
        return $this->integer[] = new IntegerColumn($column);
    }

    public function id(string $column = 'id')
    {
        $this->id = (new IntegerColumn($column))->unsigned()->increments()->primary();
    }

    public function text(string $column): TextColumn
    {
        return $this->text[] = new TextColumn($column);
    }

    public function timestamp(string $column): TimestampColumn
    {
        return $this->timestamps[] = new TimestampColumn($column);
    }

    public function timestamps()
    {
        $this->timestamp('created_at')->nullable()->default('NULL');
        $this->timestamp('updated_at')->nullable()->default('NULL');
    }

    public function foreign(string $column): Foreign
    {
        return $this->foreign[] = new Foreign($column);
    }

    public function primary(string $index): void
    {
        $this->primary[] = new Primary($index);
    }

    public function unique(string $index): void
    {
        $this->unique[] = new Unique($index);
    }

    public function index(string $index): void
    {
        $this->index[] = new Index($index);
    }

    public function fullText(string $index): void
    {
        $this->fullText[] = new FullText($index);
    }

    public function spatialIndex(string $index): void
    {
        $this->spatialIndex[] = new SpatialIndex($index);
    }

    public function dropColumn(string|array $column): void
    {
        if (is_array($column)) {
            foreach ($column as $item) {
                $this->dropColumn[] = new DropColumn($item);
            }
        } else {
            $this->dropColumn[] = new DropColumn($column);
        }
    }

    public function dropForeign(string|array $index): void
    {
        if (is_array($index)) {
            foreach ($index as $item) {
                $this->dropForeign[] = new DropForeign($item);
            }
        } else {
            $this->dropForeign[] = new DropForeign($index);
        }
    }

    public function dropPrimary(string $index): void
    {
        $this->dropPrimary[] = new DropPrimary($index);
    }

    public function dropUnique(string $index): void
    {
        $this->dropUnique[] = new DropUnique($index);
    }

    public function dropIndex(string $index): void
    {
        $this->dropIndex[] = new DropIndex($index);
    }

    public function dropFullText(string $index): void
    {
        $this->dropFullText[] = new DropFullText($index);
    }

    public function dropSpatialIndex(string $index): void
    {
        $this->dropSpatialIndex[] = new DropSpatialIndex($index);
    }
}