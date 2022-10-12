<?php

namespace Faber\Core\Providers;

use Faber\Core\Cookie\Cookie;

class CookieServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Cookie::class, $this->reflection->createObject(Cookie::class));
    }
}