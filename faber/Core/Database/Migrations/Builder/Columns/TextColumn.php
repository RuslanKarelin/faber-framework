<?php

namespace Faber\Core\Database\Migrations\Builder\Columns;

class TextColumn extends Column
{
    public function __construct(string $column)
    {
        parent::__construct($column);
        return $this;
    }
}