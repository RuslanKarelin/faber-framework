<?php

namespace Faber\Core\Console\Commands;

use Faber\Core\Console\Command;
use Faber\Core\Console\Helper;
use Faber\Core\Console\Writer\Writer;

class CommandList extends Command
{
    protected static string $signature = 'command:list';
    protected static string $description = 'Show command list';

    public function handle(Helper $helper)
    {
        $dataTable = [['Signature', 'Description']];
        foreach ($helper->getCommands() as $command) {
            $commandClass = $helper->getNamespace(pathinfo($command));
            if (!$commandClass) continue;
            $dataTable[] = [
                $commandClass::getSignature(),
                $commandClass::getDescription()
            ];
        }
        Writer::table($dataTable);
    }
}