<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

class LongTextColumn extends Column
{
    public function __construct(string $column)
    {
        parent::__construct($column);
        return $this;
    }
}