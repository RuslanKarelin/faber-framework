<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;
use Faber\Core\Console\Writer\Writer;

class MakeRequest extends Command
{
    protected static string $signature = 'make:request {request}';

    protected static string $description = 'FormRequest generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/FormRequestStub',
            'app/Requests/',
            $this->argument('request')
        );
        Writer::success('Successful create request: ' . $this->argument('request'));
    }
}