<?php

namespace Faber\Core\Session;

use SessionHandlerInterface;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Session\Handlers\FileSessionHandler;

class SessionFactory
{
    public static function createHandler(string $driver): SessionHandlerInterface
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        return $reflection->createObject(match ($driver) {
            default => FileSessionHandler::class,
        });
    }
}