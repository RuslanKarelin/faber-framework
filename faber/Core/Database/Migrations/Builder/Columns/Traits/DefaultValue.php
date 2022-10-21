<?php

namespace Faber\Core\Database\Migrations\Builder\Columns\Traits;

trait DefaultValue
{
    protected mixed $default = null;

    public function default(mixed $value): static
    {
        $this->default = $value;
        return $this;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }
}