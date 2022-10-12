<?php

namespace Faber\Core\Facades;

use Faber\Core\Contracts\Filesystem\Filesystem;

/**
 * @method static Filesystem disk(string $name)
 * @method static bool put(string $path, string $data)
 * @method static string get(string $path)
 * @method static bool append(string $path, string $data)
 * @method static string|false read(string $path)
 * @method static string|null upload(array $data)
 *
 * @see Filesystem
 */
class Storage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Filesystem";
    }
}