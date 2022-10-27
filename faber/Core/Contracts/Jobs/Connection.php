<?php

namespace Faber\Core\Contracts\Jobs;

use Faber\Core\Jobs\Job;
use Faber\Core\Utils\Collection;

interface Connection
{
    public function getJobs(string $queue): Collection;

    public function setQueue(Queue $job);

    public function deleteQueue(Job $job);

    public function setFailed(Job $job, string $exceptionMessage);
}