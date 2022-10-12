<?php

namespace Faber\Core\Facades;

use Faber\Core\Cookie\Cookie as CCookie;

/**
 * @method static bool set(string $name, string $value = "", int $expires_or_options = 0, string $path = "", string $domain = "", bool $secure = false, bool $httponly = false)
 * @method static mixed get(string $name, string $default = '')
 * @method static delete(string $name)
 *
 *
 * @see CCookie
 */
class Cookie extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Cookie";
    }
}