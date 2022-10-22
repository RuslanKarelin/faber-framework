<?php

namespace Faber\Core\Console\Commands\Database;

use Faber\Core\Console\Command;
use Faber\Core\Console\Helper;
use Faber\Core\Contracts\Filesystem\Filesystem;
use Faber\Core\Database\Migrations\Models\Migration;
use Faber\Core\Console\Writer\Writer;
use Faber\Core\Utils\Collection;

class MigrateReset extends Command
{
    protected static string $signature = 'migrate:reset';
    protected static string $description = 'Reset migrations';

    protected function getMigrations(): Collection
    {
        return Migration::select('id', 'migration')
            ->orderBy('batch', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function handle(Filesystem $filesystem, Helper $helper)
    {
        $helper->rollbackMigrations($this->getMigrations(), $filesystem);
        Writer::success('Successful reset migrations');
    }
}