<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;

class MakeProvider extends Command
{
    protected static string $signature = 'make:provider {provider}';

    protected static string $description = 'Provider generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/ProviderStub',
            'app/Providers/',
            $this->argument('provider')
        );
    }
}