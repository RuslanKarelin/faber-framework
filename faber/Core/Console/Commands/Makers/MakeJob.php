<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;
use Faber\Core\Console\Writer\Writer;

class MakeJob extends Command
{
    protected static string $signature = 'make:job {job}';

    protected static string $description = 'Job generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/JobStub',
            'app/Jobs/',
            $this->argument('job')
        );
        Writer::success('Successful create job: ' . $this->argument('job'));
    }
}