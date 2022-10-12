<?php

namespace Faber\Core\Middleware;

use Faber\Core\Contracts\Database\Connection as DBConnection;
use Faber\Core\DI\Container;
use Faber\Core\Request\Request;
use Closure;

class Database
{
    public function handle(Request $request, Closure $next)
    {
        Container::getInstance()->get(DBConnection::class)->close();
        return $next($request);
    }
}