<?php

namespace Faber\Core\Providers;

use Faber\Core\Response\Response;

class ResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Response::class, $this->reflection->createObject(Response::class));
    }
}