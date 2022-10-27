<?php

namespace Faber\Core\Console\Commands;

use Faber\Core\Console\Command;
use Faber\Core\Console\Writer\Writer;
use Faber\Core\Jobs\Worker;

class Work extends Command
{
    protected static string $signature = 'queue:work {--queue?}';
    protected static string $description = 'Start processing jobs on the queue as a daemon';

    public function __construct(protected Worker $worker)
    {
    }

    public function handle()
    {
        Writer::success('Worker starting');
        $this->worker->daemon($this->argument('queue') ?? 'default');
    }
}