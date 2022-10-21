<?php

namespace Faber\Core\Console\Commands\Database;

use Faber\Core\Console\Command;
use Faber\Core\Exceptions\DBException;
use Faber\Core\Facades\DB;
use Faber\Core\Filesystem\Finder;
use Faber\Core\Database\Migrations\Models\Migration;
use Faber\Core\Console\Writer\Writer;

class Migrate extends Command
{
    protected static string $signature = 'migrate';

    protected static string $description = 'Performing migrations';

    const MIGRATIONS_TABLE = 'migrations';

    protected function writeResult(bool $initialize, array $successfulMigrations = []): void
    {
        if (!$initialize) {
            if ($successfulMigrations) {
                Migration::insert($successfulMigrations);
                Writer::success('Successful migrations: ');
                foreach ($successfulMigrations as $migration) {
                    Writer::success($migration['migration']);
                }
            }
        }
    }

    protected function up($path, bool $initialize = false, int $batch = 1): void
    {
        $successfulMigrations = [];
        $files = (new Finder())->path($path)->files()->getFiles();
        foreach ($files as $file) {
            $object = new (include $path . DIRECTORY_SEPARATOR . $file)();
            try {
                $migrationFileName = pathinfo($path . DIRECTORY_SEPARATOR . $file, PATHINFO_FILENAME);
                $object->up();
                $successfulMigrations[] = [
                    'migration' => $migrationFileName,
                    'batch' => $batch
                ];
            } catch (\Exception $exception) {
                $this->writeResult($initialize, $successfulMigrations);
                Writer::fail(['Fail migrations:', $migrationFileName, $exception->getMessage()]);
                exit;
            }
        }

        $this->writeResult($initialize, $successfulMigrations);
    }

    /**
     * @throws DBException
     */
    public function handle()
    {
        $batch = 1;
        if (!DB::tableExists(static::MIGRATIONS_TABLE)) {
            $this->up(dirname(__DIR__) . '/../../Database/Migrations/migrations', true);
        } else {
            $object = Migration::select('batch')
                ->orderBy('batch', 'desc')
                ->limit(1)
                ->get()
                ->first();
            if ($object) $batch = ++$object->batch;
        }
        $this->up(root_path(config('database.migrationPath')), false, $batch);
    }
}