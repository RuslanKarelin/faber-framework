<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Database\Paginator as IPaginator;
use Faber\Core\Utils\Paginator;

class PaginatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(IPaginator::class, Paginator::class);
    }
}