<?php

namespace Faber\Core\Database\Migrations\Builder;

use Faber\Core\Contracts\Database\Migrations\Builder;
use Faber\Core\Contracts\Database\Migrations\MigrationService;
use Faber\Core\Database\Migrations\Builder\Columns\IntegerColumn;
use Faber\Core\Database\Migrations\Builder\Columns\StringColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TextColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TimestampColumn;
use Faber\Core\Database\Migrations\Builder\Foreign\Foreign;

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

    abstract protected function getIdColumn(): string;
    abstract protected function getStringColumn(StringColumn $column): string;
    abstract protected function getIntegerColumn(IntegerColumn $column): string;
    abstract protected function getTextColumn(TextColumn $column): string;
    abstract protected function getTimestampColumn(TimestampColumn $column): string;
    abstract protected function getForeign(Foreign $foreign): string;
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
}