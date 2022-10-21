<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

use Faber\Core\Database\Migrations\Builder\Columns\Traits\DefaultValue;

class IntegerColumn extends Column
{
    use DefaultValue;

    protected bool $unsigned = false;
    protected bool $autoIncrement = false;
    protected bool $primaryKey = false;

    public function __construct(string $column)
    {
        parent::__construct($column);
        return $this;
    }

    public function unsigned(): static
    {
        $this->unsigned = true;
        return $this;
    }

    public function increments(): static
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function primary(): static
    {
        $this->primaryKey = true;
        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function isPrimaryKey(): bool
    {
        return $this->primaryKey;
    }
}