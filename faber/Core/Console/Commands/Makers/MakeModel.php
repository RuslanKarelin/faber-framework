<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;

class MakeModel extends Command
{
    protected static string $signature = 'make:model {model}';

    protected static string $description = 'Model generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/ModelStub',
            'app/Models/',
            $this->argument('model')
        );
    }
}