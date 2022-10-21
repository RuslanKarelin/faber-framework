<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

use Faber\Core\Database\Migrations\Builder\Columns\Traits\DefaultValue;

class StringColumn extends Column
{
    use DefaultValue;

    public function __construct(string $column, protected int $length = 255)
    {
        parent::__construct($column);
        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}