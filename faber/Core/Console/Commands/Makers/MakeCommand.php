<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;
use Faber\Core\Console\Writer\Writer;

class MakeCommand extends Command
{
    protected static string $signature = 'make:command {command}';

    protected static string $description = 'Command generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/CommandStub',
            'app/Console/Commands/',
            $this->argument('command')
        );
        Writer::success('Successful create command: ' . $this->argument('command'));
    }
}