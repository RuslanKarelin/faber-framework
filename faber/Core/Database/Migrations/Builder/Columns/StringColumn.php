<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

use Faber\Core\Database\Migrations\Builder\Columns\Traits\DefaultValue;
use Faber\Core\Database\Migrations\Builder\Columns\Traits\Unique;

class StringColumn extends Column
{
    use DefaultValue, Unique;

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