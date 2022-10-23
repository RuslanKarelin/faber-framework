<?php

namespace Faber\Core\Database\Migrations\Builder\Drop;

class DropColumn
{
    public function __construct(protected string $column)
    {
    }

    public function getColumn(): string
    {
        return $this->column;
    }
}