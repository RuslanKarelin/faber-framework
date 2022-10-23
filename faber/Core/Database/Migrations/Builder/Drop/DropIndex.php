<?php

namespace Faber\Core\Database\Migrations\Builder\Drop;

class DropIndex
{
    public function __construct(protected string $index)
    {
    }

    public function getIndex(): string
    {
        return $this->index;
    }
}