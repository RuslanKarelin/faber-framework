<?php

namespace Faber\Core\Utils;

use ArrayIterator;
use IteratorAggregate;

class Collection implements IteratorAggregate
{
    public function __construct(
        protected array $items = []
    )
    {
    }

    public function push(mixed $item)
    {
        $this->items[] = $item;
    }

    public function first()
    {
        return $this->items[0] ?? null;
    }

    public function last()
    {
        return $this->items[$this->count() - 1];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function pluck(string $field): static
    {
        $items = array_map(function ($it) use ($field) {
            return $it->{$field};
        }, $this->items);
        return new static($items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}