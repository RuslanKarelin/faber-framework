<?php

namespace Faber\Core\Providers;

use Faber\Core\Route\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Route::class, $this->reflection->createObject(Route::class));
        $this->container->bind('Route', Route::class);
    }
}