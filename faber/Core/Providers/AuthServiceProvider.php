<?php

namespace Faber\Core\Providers;

use Faber\Core\Auth\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Auth::class, $this->reflection->createObject(Auth::class));
        $this->container->bind('Auth', Auth::class);
    }
}