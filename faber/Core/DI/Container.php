<?php

namespace Faber\Core\DI;

class Container
{
    private function __construct()
    {
    }

    protected static ?Container $staticInstance = null;
    protected static array $instances = [];
    protected static array $bindings = [];

    public static function getInstances(): array
    {
        return static::$instances;
    }

    public static function getBindings(): array
    {
        return static::$bindings;
    }

    public static function getInstance(): Container
    {
        if (!static::$staticInstance) {
            static::$staticInstance = new static();
        }
        return static::$staticInstance;
    }

    public function singleton(string $className, mixed $instance): void
    {
        static::$instances[$className] = $instance;
    }

    public function bind(string $key, string $value): void
    {
        static::$bindings[$key] = $value;
    }

    public function get(string $className): mixed
    {
        if (array_key_exists($className, static::$instances)) {
            return static::$instances[$className];
        }
        return null;
    }

    public function getRespond(string $name): mixed
    {
        if (array_key_exists($name, static::$bindings)) {
            return static::$bindings[$name];
        }
        return null;
    }
}