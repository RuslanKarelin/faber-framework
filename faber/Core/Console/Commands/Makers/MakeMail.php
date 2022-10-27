<?php

namespace Faber\Core\Console\Commands\Makers;

use Faber\Core\Console\Command;
use Faber\Core\Console\MakerFromStub;
use Faber\Core\Console\Writer\Writer;

class MakeMail extends Command
{
    protected static string $signature = 'make:mail {mail}';

    protected static string $description = 'Mail generation';

    public function handle(MakerFromStub $makerFromStub)
    {
        $makerFromStub->make(
            dirname(__DIR__) . '/../Stubs/MailStub',
            'app/Mail/',
            $this->argument('mail')
        );
        Writer::success('Successful create mail: ' . $this->argument('mail'));
    }
}