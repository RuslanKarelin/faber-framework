<?php

namespace Faber\Core\Database\Migrations\Builder\Columns\Traits;

trait Unique
{
    protected bool $unique = false;

    public function unique(): static
    {
        $this->unique = true;
        return $this;
    }

    public function getUnique(): bool
    {
        return $this->unique;
    }
}