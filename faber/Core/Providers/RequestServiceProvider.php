<?php

namespace Faber\Core\Providers;

use Faber\Core\Request\Request;

class RequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Request::class, $this->reflection->createObject(Request::class));
        $this->container->bind('Request', Request::class);
    }
}