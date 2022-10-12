<?php

namespace Faber\Core\Utils;

class MessageBag
{
    protected array $messages = [];

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    public function add(string $key, string $value): void
    {
        if (!array_key_exists($key, $this->messages)) {
            $this->messages[$key] = [];
        }
        $this->messages[$key][] = $value;
    }

    public function get(string $key): array
    {
        return !empty($this->messages[$key]) ? $this->messages[$key] : [];
    }

    public function first(string $key): string
    {
        if (!empty($this->messages[$key])) {
            $indexKey = array_key_first($this->messages[$key]);
            return $this->messages[$key][$indexKey];
        }
        return '';
    }

    public function getByRule(string $key, string $rule): string
    {
        if (!empty($this->messages[$key][$rule])) {
            return $this->messages[$key][$rule];
        }
        return '';
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->messages);
    }

    public function all(): array
    {
        return $this->messages;
    }

    public function isEmpty(): bool
    {
        return empty($this->messages);
    }
}