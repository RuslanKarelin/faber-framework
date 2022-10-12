<?php

namespace Faber\Core\Cookie;

class Cookie
{
    public function set(
        string $name,
        string $value = "",
        int    $expires_or_options = 0,
        string $path = "",
        string $domain = "",
        bool   $secure = false,
        bool   $httponly = false
    ): bool
    {
        return setcookie(
            $name,
            $value,
            $expires_or_options,
            $path,
            $domain,
            $secure,
            $httponly
        );
    }

    public function get(string $name, mixed $default = ''): mixed
    {
        return (!empty($_COOKIE[$name])) ? $_COOKIE[$name] : $default;
    }

    public function delete(string $name, string $path): void
    {
        setcookie($name, '', -1, $path);
        unset($_COOKIE[$name]);
    }
}