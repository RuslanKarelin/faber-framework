<?php

use Faber\Core\Auth\Auth;
use Faber\Core\Config\Config;
use Faber\Core\DI\Container;
use Faber\Core\Helpers\App;
use Faber\Core\Lang\Lang;
use Faber\Core\Request\Request;
use Faber\Core\Facades\Route;
use Faber\Core\Utils\Collection;
use Faber\Core\Facades\Session;
use Faber\Core\Enums\Session as SessionEnum;
use Faber\Core\Route\Route as CRoute;
use Faber\Core\Response\Response;

if (!function_exists('app')) {
    function app(): Container
    {
        return Container::getInstance();
    }
}

if (!function_exists('collect')) {
    function collect(array $items = []): Collection
    {
        return new Collection($items);
    }
}

if (!function_exists('request')) {
    function request(): Request
    {
        return Container::getInstance()->get(Request::class);
    }
}

if (!function_exists('response')) {
    function response(): Response
    {
        return Container::getInstance()->get(Response::class);
    }
}

if (!function_exists('route')) {
    function route(?string $name = null, array $params = []): string|CRoute
    {
        if (!$name) return app()->get(CRoute::class);
        return Route::getUrlByRouteName($name, $params);
    }
}

if (!function_exists('dd')) {
    function dd(...$args): void
    {
        foreach ($args as $arg) {
            echo "<pre>";
            print_r($arg);
            echo "</pre>";
        }
        die;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $_ENV) ? $_ENV[$key] : $default;
    }
}

if (!function_exists('config')) {
    function config(string $key): mixed
    {
        $array = explode('.', $key);
        $configArray = (app()->get(Config::class))->getConfig();
        foreach ($array as $key) {
            if (array_key_exists($key, $configArray)) {
                $configArray = $configArray[$key];
            } else {
                $configArray = null;
                break;
            }
        }
        return $configArray ?? null;
    }
}

if (!function_exists('root_path')) {
    function root_path(string $path = ''): string
    {
        $root = rtrim($_SERVER['DOCUMENT_ROOT'], 'public/');
        $root = $root ?:  dirname(__DIR__).'/../../';
        return $root . '/' . $path;
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return root_path() . 'storage/' . $path;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return root_path() . 'public/' . $path;
    }
}

if (!function_exists('resources_path')) {
    function resources_path(string $path = ''): string
    {
        return root_path() . 'resources/' . $path;
    }
}

if (!function_exists('trans')) {
    function trans(string $string, array $data = []): ?string
    {
        $array = explode('.', $string);
        $langArray = (app()->get(Lang::class))->getLang()[App::getLocale()];
        foreach ($array as $key) {
            if (array_key_exists($key, $langArray)) {
                $langArray = $langArray[$key];
            } else {
                $langArray = null;
                break;
            }
        }
        if ($langArray && $data) {
            $keys = array_map(function (string $it): string {
                return ':' . $it;
            }, array_keys($data));
            $langArray = str_replace($keys, array_values($data), $langArray);
        }
        return $langArray ?? null;
    }
}

if (!function_exists('old')) {
    function old(string $field): ?string
    {
        $old = Session::get(SessionEnum::OLD);
        if ($old && isset($old[$field])) {
            return $old[$field];
        }
        return null;
    }
}

if (!function_exists('auth')) {
    function auth(): Auth
    {
        return Container::getInstance()->get(Auth::class);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): ?string
    {
        return Session::token();
    }
}