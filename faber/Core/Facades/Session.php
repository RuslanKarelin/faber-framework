<?php

namespace Faber\Core\Facades;

use Faber\Core\Session\Store as SessionStore;

/**
 * @method static string getId()
 * @method static string generateSessionId()
 * @method static void setAttributes(array $attributes)
 * @method static bool has(string $key)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void setId($id)
 * @method static void set(string $key, mixed $value)
 * @method static bool delete(string $key)
 * @method static void flash(string $key, string $value)
 * @method static void error(string $key, string $value, string $rule = '')
 * @method static string|null token()
 * @method static void generateToken()
 *
 * @see SessionStore
 */
class Session extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Session";
    }
}