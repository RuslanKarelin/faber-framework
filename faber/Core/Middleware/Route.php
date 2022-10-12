<?php

namespace Faber\Core\Middleware;

use Faber\Core\DI\Container;
use Faber\Core\Request\Request;
use \Faber\Core\Route\Route as CRoute;
use Closure;

class Route
{
    public function handle(Request $request, Closure $next)
    {
        Container::getInstance()->get(CRoute::class)->addRoutes()->setCurrentRoute();
        return $next($request);
    }
}