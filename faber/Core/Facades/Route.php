<?php

namespace Faber\Core\Facades;

use Faber\Core\Route\Route as CRoute;

/**
 * @method static CRoute middleware(array|string $middleware)
 * @method static CRoute group(\Closure $closure)
 * @method static CRoute get(string $pattern, array|\Closure $action = [])
 * @method static CRoute post(string $pattern, array|\Closure $action = [])
 * @method static CRoute patch(string $pattern, array|\Closure $action = [])
 * @method static CRoute put(string $pattern, array|\Closure $action = [])
 * @method static CRoute delete(string $pattern, array|\Closure $action = [])
 * @method static CRoute name(string $routeName)
 * @method static CRoute prefix(string $prefix)
 * @method static CRoute addMiddleware(array|string $middleware)
 * @method static array getRoutes()
 * @method static string getUrlByRouteName(string $name, array $params = [])
 *
 * @see CRoute
 */
class Route extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Route";
    }
}