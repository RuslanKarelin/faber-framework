<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Database\Builder;
use Faber\Core\Database\Builder\DBBuilderFactory;

class QueryBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(Builder::class, DBBuilderFactory::getBuilderClass(config('database.default')));
    }
}