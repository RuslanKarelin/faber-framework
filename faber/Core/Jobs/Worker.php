<?php

namespace Faber\Core\Jobs;

use Faber\Core\Console\Writer\Writer;
use Faber\Core\Contracts\Jobs\Connection;
use Faber\Core\DI\Reflection;
use Faber\Core\Facades\Log;
use Faber\Core\Utils\Collection;

class Worker
{
    public function __construct(
        protected Connection $connection,
        protected Reflection $reflection
    )
    {
    }

    public function daemon(string $queue)
    {
        while (true) {
            try {
                foreach ($this->getJobs($queue) as $job) {
                    try {
                        $this->reflection->handleMethod($job->getPayload(), 'handle');
                        $this->connection->deleteQueue($job);
                    } catch (\Exception $exception) {
                        $message = $exception->getMessage();
                        $this->connection->setFailed($job, $message);
                        Writer::fail($message);
                    }
                    sleep(1);
                }
            } catch (\Exception $exception) {
                $message = $exception->getMessage();
                Log::error($message);
                Writer::fail($message);
            }
            sleep(2);
        }
    }

    public function getJobs(string $queue): Collection
    {
        return $this->connection->getJobs($queue);
    }
}