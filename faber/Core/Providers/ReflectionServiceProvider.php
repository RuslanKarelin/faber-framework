<?php

namespace Faber\Core\Providers;

use Faber\Core\DI\Reflection;

class ReflectionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Reflection::class, $this->reflection);
    }
}