<?php

namespace Faber\Core\Database\Migrations\Builder\Foreign;

class Foreign
{
    protected string $references;
    protected string $on;
    protected string $onDelete;

    public function getReferences(): string
    {
        return $this->references;
    }

    public function getOn(): string
    {
        return $this->on;
    }

    public function getOnDelete(): string
    {
        return $this->onDelete;
    }

    public function __construct(protected string $column)
    {
    }

    public function references(string $column): static
    {
        $this->references = $column;
        return $this;
    }

    public function on(string $table): static
    {
        $this->on = $table;
        return $this;
    }

    public function onDelete(string $key): static
    {
        $this->onDelete = $key;
        return $this;
    }

    public function getColumn(): string
    {
        return $this->column;
    }
}