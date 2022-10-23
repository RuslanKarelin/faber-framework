<?php

namespace Faber\Core\Database\Migrations\Builder\Index;

class Index
{
    public function __construct(protected string $index)
    {
    }

    public function getIndex(): string
    {
        return $this->index;
    }
}