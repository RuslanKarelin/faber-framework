<?php

namespace Faber\Core\Middleware;

use Faber\Core\DI\Container;
use Faber\Core\Request\Request;
use Faber\Core\Route\Route;
use Faber\Core\DI\Reflection;

class Middleware
{
    protected mixed $next;
    protected mixed $handler;

    public function getNext(): mixed
    {
        return $this->next;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function setNext($next)
    {
        $this->next = $next;
        return $next;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $handler;
    }

    public static function handle(Middleware $middleware): Middleware
    {
        if ($handler = $middleware->getHandler()) {
            $request = Container::getInstance()->get(Request::class);
            $next = $middleware->getNext();
            $handler->handle($request, function ($request) use ($next) {
                return $next ? static::handle($next) : null;
            });
        }
        return $middleware;
    }

    public static function routeProcess(): void
    {
        $currentRouteMiddlewares = Container::getInstance()->get(Route::class)->current()['middleware'];
        $middlewaresList = [];

        if ($routeMiddlewares = config('app.routeMiddleware')) {
            $middlewaresList = array_filter($routeMiddlewares, function($middleware) use ($currentRouteMiddlewares) {
                if (in_array($middleware, $currentRouteMiddlewares)) return true;
                return false;
            }, ARRAY_FILTER_USE_KEY);
        }
        static::processChains($middlewaresList);
    }

    protected static function processChains(array $middlewaresList): void
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        $middlewares = [];
        foreach ($middlewaresList as $middlewareClassName) {
            $middleware = new static();
            $middleware->setHandler($reflection->createObject($middlewareClassName));
            $middlewares[] = $middleware;
        }
        foreach ($middlewares as $middleware) {
            $middleware->setNext(next($middlewares));
        }
        if (!empty($middlewares)) static::handle($middlewares[0]);
    }

    public static function process(): void
    {
        if ($appMiddlewares = config('app.middleware')) {
            static::processChains($appMiddlewares);
        }
    }

    public static function processAfter(): void
    {
        if ($afterMiddlewares = config('app.afterMiddleware')) {
            static::processChains($afterMiddlewares);
        }
    }
}