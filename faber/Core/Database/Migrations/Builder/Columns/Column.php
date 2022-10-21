<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

class Column
{
    protected bool $isNull = false;

    public function __construct(protected string $column)
    {
    }

    public function nullable(): static
    {
        $this->isNull = true;
        return $this;
    }

    public function isNull(): bool
    {
        return $this->isNull;
    }

    public function getColumn(): string
    {
        return $this->column;
    }
}