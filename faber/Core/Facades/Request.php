<?php

namespace Faber\Core\Facades;

use Faber\Core\Cookie\Cookie;
use Faber\Core\Models\Model;
use Faber\Core\Request\Request as CRequest;
use Faber\Core\Session\Store as SessionStore;

/**
 * @method static bool has(string $key)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void set(string $key, mixed $value)
 * @method static array all()
 * @method static array files()
 * @method static array only(array $keys = [])
 * @method static array exclude(array $keys = [])
 * @method static Cookie cookie()
 * @method static bool isAjax()
 * @method static bool isPost()
 * @method SessionStore session()
 * @method Model user()
 * @method string fullUrl()
 * @method bool isUnsafeMethod()
 *
 * @see CRequest
 */
class Request extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Request";
    }
}