<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;
use Faber\Core\Console\Writer\Writer;

class MakeMigration extends Command
{
    protected static string $signature = 'make:migration {migration}';
    protected static string $description = 'Migration generation';

    protected function getTableName(): string
    {
        $tableName = 'table';
        if (preg_match('#(create|update) (.+) table#', $this->argument('migration'), $match))
            $tableName = $match[2];
        return $tableName;
    }

    public function handle(MakerFromStub $makerFromStub)
    {
        $stub = 'MigrationUpdateStub';
        if (str_starts_with($this->argument('migration'), 'create')) $stub = 'MigrationCreateStub';
        $migrationName = date('Y_m_d_his') . '_' . str_replace(
                ' ',
                '_',
                $this->argument('migration')
            );
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/' . $stub,
            config('database.migrationPath') . DIRECTORY_SEPARATOR,
            $migrationName,
            ['from' => [':tableName'], 'to' => [$this->getTableName()]]
        );
        Writer::success('Successful create migration: ' . $migrationName);
    }
}