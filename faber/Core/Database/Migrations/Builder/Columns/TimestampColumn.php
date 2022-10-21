<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

use Faber\Core\Database\Migrations\Builder\Columns\Traits\DefaultValue;

class TimestampColumn extends Column
{
    use DefaultValue;

    public function __construct(string $column)
    {
        parent::__construct($column);
        return $this;
    }
}