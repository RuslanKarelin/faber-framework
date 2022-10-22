<?php

namespace Faber\Core\Console;

use Faber\Core\Console\Writer\Writer;
use Faber\Core\Contracts\Filesystem\Filesystem;
use Faber\Core\Database\Migrations\Models\Migration;
use Faber\Core\Enums\App;
use Faber\Core\Filesystem\Finder;
use Faber\Core\Helpers\Str;
use Faber\Core\Utils\Collection;

class Helper
{
    protected function prepareNamespace(string $folderName, array $pathInfo): string
    {
        $dirName = explode($folderName, $pathInfo['dirname']);
        return Str::ucfirst($folderName) .
            str_replace('/', '\\', $dirName[1]) . '\\' . $pathInfo['filename'];
    }

    public function __construct(protected Filesystem $filesystem)
    {
    }

    public function getCommands(): array
    {
        $appCommandsPath = root_path('app/Console/Commands');
        return array_merge(
            (new Finder())->path(dirname(__DIR__) . '/Console/Commands')->recursive()->getFiles(),
            $this->filesystem->exist($appCommandsPath) ?
                (new Finder())->path($appCommandsPath)->recursive()->getFiles() : []
        );
    }

    public function getNamespace(array $pathInfo): ?string
    {
        $namespace = null;
        if (str_contains($pathInfo['dirname'], App::FRAMEWORK_FOLDER)) {
            $namespace = $this->prepareNamespace(App::FRAMEWORK_FOLDER, $pathInfo);
        } elseif (str_contains($pathInfo['dirname'], App::APP_FOLDER)) {
            $namespace = $this->prepareNamespace(App::APP_FOLDER, $pathInfo);
        }
        return $namespace;
    }

    public function rollbackMigrations(Collection $migrations, Filesystem $filesystem): void
    {
        $path = root_path(config('database.migrationPath'));
        $ids = [];
        foreach ($migrations as $migration) {
            $filePath = $path . DIRECTORY_SEPARATOR . $migration->migration . '.php';
            if ($filesystem->exist($filePath)) {
                $object = new (include $filePath);
                try {
                    $object->down();
                    $ids[] = $migration->id;
                } catch (\Exception $exception) {
                    Writer::fail(['Fail migration:' . $migration->migration, $exception->getMessage()]);
                    exit;
                }
            } else {
                Writer::fail("Migration file {$migration->migration} not found");
                exit;
            }
        }
        if ($ids) Migration::whereIn('id', $ids)->destroy();
    }
}