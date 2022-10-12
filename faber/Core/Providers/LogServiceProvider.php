<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Log\Log as ILog;
use Faber\Core\Log\Log;

class LogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(ILog::class, Log::class);
    }
}