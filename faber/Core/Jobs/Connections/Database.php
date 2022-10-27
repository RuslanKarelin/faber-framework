<?php

namespace Faber\Core\Jobs\Connections;

use Faber\Core\Contracts\Jobs\Queue;
use Faber\Core\Database\Migrations\Models\Job;
use Faber\Core\Utils\Collection;
use Faber\Core\Jobs\Job as EntityJob;

class Database extends AbstractConnection
{
    public function getJobs(string $queue): Collection
    {
        $jobs = collect([]);
        foreach (Job::where('queue', $queue)->get() as $job) {
            $jobs->push(new EntityJob(
                $job->id,
                $job->payload,
                $job->queue,
                $job->attempts
            ));
        }
        return $jobs;
    }

    public function setQueue(Queue $job)
    {
        Job::create([
            'queue' => $job->getQueue(),
            'payload' => serialize($job),
            'attempts' => $job->getAttempts()
        ]);
    }

    public function deleteQueue(EntityJob $job)
    {
        Job::destroy($job->getId());
    }
}