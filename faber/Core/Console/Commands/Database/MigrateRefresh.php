<?php

namespace Faber\Core\Console\Commands\Database;

use Faber\Core\Console\Command;
use Faber\Core\DI\Reflection;

class MigrateRefresh extends Command
{
    protected static string $signature = 'migrate:refresh';
    protected static string $description = 'Refresh migrations';

    public function handle(Reflection $reflection)
    {
        $reflection->handleMethod($reflection->createObject(MigrateReset::class), 'handle');
        $reflection->handleMethod($reflection->createObject(Migrate::class), 'handle');
    }
}