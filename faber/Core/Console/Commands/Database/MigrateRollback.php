<?php

namespace Faber\Core\Console\Commands\Database;

use Faber\Core\Console\Command;
use Faber\Core\Console\Helper;
use Faber\Core\Contracts\Filesystem\Filesystem;
use Faber\Core\Database\Migrations\Models\Migration;
use Faber\Core\Console\Writer\Writer;
use Faber\Core\Utils\Collection;

class MigrateRollback extends Command
{
    protected static string $signature = 'migrate:rollback {--step?}';
    protected static string $description = 'Rollback migrations';

    protected function getMigrations(): Collection
    {
        $step = $this->argument('step') ?? 1;
        $batch = Migration::select('batch')
            ->orderBy('batch', 'desc')
            ->groupBy('batch')
            ->limit($step)
            ->get()
            ->pluck('batch')
            ->toArray();
        if ($batch) {
            return Migration::select('id', 'migration', 'batch')
                ->orderBy('batch', 'desc')
                ->orderBy('id', 'desc')
                ->whereIn('batch', $batch)
                ->get();
        }
        return collect([]);
    }

    public function handle(Filesystem $filesystem, Helper $helper)
    {
        $helper->rollbackMigrations($this->getMigrations(), $filesystem);
        Writer::success('Successful rollback migrations');
    }
}