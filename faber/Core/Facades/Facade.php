<?php

namespace Faber\Core\Facades;

use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;

abstract class Facade
{
    public static function __callStatic(string $name, array $arguments): mixed
    {
        $className = static::class;
        if (!method_exists($className, $name)) {
            $container = Container::getInstance();
            $object = $container->get(Reflection::class)
                ->createObject(
                    $container->getRespond(static::getFacadeAccessor())
                );
            static::prepareInstance($object);
            return $object->{$name}(...$arguments);
        }
        return $className::{$name}(...$arguments);
    }

    protected static function prepareInstance(mixed $instance): void
    {
    }

    abstract protected static function getFacadeAccessor(): string;
}