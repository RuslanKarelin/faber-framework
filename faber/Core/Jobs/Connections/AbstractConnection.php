<?php

namespace Faber\Core\Jobs\Connections;

use Faber\Core\Contracts\Jobs\Connection;
use Faber\Core\Contracts\Jobs\Queue;
use Faber\Core\Database\Migrations\Models\FailedJob;
use Faber\Core\Utils\Collection;
use Faber\Core\Jobs\Job as EntityJob;

abstract class AbstractConnection implements Connection
{
    abstract public function getJobs(string $queue): Collection;

    abstract public function setQueue(Queue $job);

    abstract public function deleteQueue(EntityJob $job);

    public function setFailed(EntityJob $job, string $exceptionMessage)
    {
        FailedJob::create(
            [
                'queue' => $job->getQueue(),
                'payload' => $job->getPayload(),
                'exception' => $exceptionMessage,
            ]
        );
    }
}