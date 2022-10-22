<?php

namespace Faber\Core\Console;

class Command
{
    protected static string $signature = '';

    protected static string $description = '';

    protected array $arguments = [];

    public static function getSignature(): string
    {
        return static::$signature;
    }

    public static function getDescription(): string
    {
        return static::$description;
    }

    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function argument(string $key, $default = null)
    {
        if (isset($this->arguments[$key])) return $this->arguments[$key];
        return $default;
    }
}