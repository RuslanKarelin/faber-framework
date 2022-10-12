<?php

namespace Faber\Core\Route;

use Closure;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Filesystem\Finder;
use Faber\Core\Helpers\Arr;
use Faber\Core\Request\Request;

class Route
{
    protected Request $request;
    protected Reflection $reflection;
    protected string $routeType = '';
    protected string $prefix = '';
    protected array $routes = [
        'get' => [],
        'post' => [],
        'patch' => [],
        'put' => [],
        'delete' => [],
    ];
    protected array $currentRoute = [];
    protected array $middlewares = [];
    protected array $params = [];
    protected array $lastAddedRoute = [];

    protected function setCurrent(array $routeParams): void
    {
        $routeParams['params'] = $this->getParams();
        unset($routeParams['paramsName']);
        $this->currentRoute = $routeParams;
    }

    protected function addRoute(string $method, string $pattern, array|Closure $action = []): static
    {
        $pattern = '/' . ltrim($pattern, '/');
        if (!empty($this->prefix)) {
            $this->prefix = ltrim($this->prefix, '/');
            $pattern = '/' . $this->prefix . $pattern;
        }

        $this->routes[$method][$pattern] = $this->lastAddedRoute = [
            'method' => $method,
            'pattern' => $pattern,
            'middleware' => Arr::flatten($this->middlewares),
            'action' => $action,
            'paramsName' => $this->getParamsName($pattern),
            'type' => $this->routeType,
        ];
        return $this;
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->reflection = Container::getInstance()->get(Reflection::class);
    }

    public function current(): array
    {
        return $this->currentRoute;
    }

    public function currentName(): string
    {
        return $this->currentRoute['name'];
    }

    public function currentType(): string
    {
        return $this->currentRoute['type'];
    }

    public function currentParams(): array
    {
        return $this->currentRoute['params'];
    }

    public function addParam(string $key, mixed $value): void
    {
        $this->currentRoute['params'][$key] = $value;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function get(string $pattern, array|Closure $action = []): static
    {
        return $this->addRoute('get', $pattern, $action);
    }

    public function post(string $pattern, array|Closure $action = []): static
    {
        return $this->addRoute('post', $pattern, $action);
    }

    public function patch(string $pattern, array|Closure $action = []): static
    {
        return $this->addRoute('patch', $pattern, $action);
    }

    public function put(string $pattern, array|Closure $action = []): static
    {
        return $this->addRoute('put', $pattern, $action);
    }

    public function delete(string $pattern, array|Closure $action = []): static
    {
        return $this->addRoute('delete', $pattern, $action);
    }

    public function name(string $routeName): static
    {
        $this->routes[$this->lastAddedRoute['method']][$this->lastAddedRoute['pattern']]['name'] = $routeName;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function addRoutes(): static
    {
        $fileList = (new Finder())->path('../routes')->recursive()->getFiles();
        foreach ($fileList as $file) {
            $this->routeType = pathinfo($file, PATHINFO_FILENAME);
            require_once $file;
        }
        return $this;
    }

    public function setCurrentRoute(): array|Closure|null
    {
        if ($this->currentRoute) return $this->currentRoute['action'];

        foreach ($this->routes[$this->request->method] as $pattern => $routeParams) {
            $pattern = preg_replace('#{(.*)}#siU', '([^/]*)', $pattern);
            if (preg_match_all(
                '#^' . $pattern . '$#siU',
                $this->request->path,
                $matches,
                PREG_SET_ORDER
            )) {
                $this->params = array_combine($routeParams['paramsName']['names'], $this->getParamsValue($matches));
                $this->setCurrent($routeParams);
                return $routeParams['action'];
            }
        }
        return null;
    }

    protected function getParamsName(string $pattern): array
    {
        preg_match_all('#{(.*)}#siU', $pattern, $matches);
        sort($matches[0]);
        sort($matches[1]);
        return [
            'original' => $matches[0],
            'names' => $matches[1]
        ];
    }

    protected function getParamsValue(array $matches): array
    {
        unset($matches[0][0]);
        $matches[0] = array_map(function ($value) {
            if ($value == '0' || intval($value) !== 0) $value = intval($value);
            return $value;
        }, $matches[0]);
        return array_values($matches[0]);
    }

    public function middleware(array|string $middleware): static
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function addMiddleware(array|string $middleware): static
    {
        $this->middleware($middleware);
        $this->routes[$this->lastAddedRoute['method']][$this->lastAddedRoute['pattern']]['middleware']
            = Arr::flatten($this->middlewares);
        $this->middlewares = [];
        return $this;
    }

    public function group(Closure $closure): static
    {
        $this->reflection->init($closure);
        array_pop($this->middlewares);
        $this->prefix = '';
        return $this;
    }

    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getUrlByRouteName(string $name, array $params = []): string
    {
        $routePath = '';
        foreach ($this->getRoutes() as $routesChunk) {
            if ($routes = array_filter($routesChunk, function ($route) use ($name) {
                if (isset($route['name']) && $route['name'] === $name) {
                    return $route;
                }
            })) {
                $route = $routes[array_key_first($routes)];
                if ($route['paramsName']) {
                    $routeParams = $queryParams = [];
                    foreach ($params as $key => $param) {
                        if (in_array($key, $route['paramsName']['names'])) {
                            $routeParams[$key] = $param;
                        } else {
                            $queryParams[$key] = $param;
                        }
                    }
                    ksort($routeParams);
                    $routePath = env('APP_URL') . str_replace($route['paramsName']['original'], array_values($routeParams), $route['pattern']);
                    if ($queryParams) {
                        $routePath .= '?' . http_build_query($queryParams);
                    }
                }
                break;
            }
        }
        return $routePath;
    }
}