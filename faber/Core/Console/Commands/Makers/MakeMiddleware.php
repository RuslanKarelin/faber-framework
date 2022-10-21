<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;
use Faber\Core\Console\Writer\Writer;

class MakeMiddleware extends Command
{
    protected static string $signature = 'make:middleware {middleware}';

    protected static string $description = 'Middleware generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/MiddlewareStub',
            'app/Middleware/',
            $this->argument('middleware')
        );
        Writer::success('Successful create middleware: ' . $this->argument('middleware'));
    }
}