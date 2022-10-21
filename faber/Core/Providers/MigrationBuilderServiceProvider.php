<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Database\Migrations\Builder;
use Faber\Core\Database\Migrations\Builder\BuilderFactory;
use Faber\Core\Contracts\Database\Migrations\MigrationService as IMigrationService;
use Faber\Core\Database\Migrations\MigrationService;

class MigrationBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(IMigrationService::class, MigrationService::class);
        $this->container->bind(Builder::class, BuilderFactory::getBuilderClass(config('database.default')));
    }
}