<?php

namespace Faber\Core\Jobs;

use Faber\Core\Contracts\Jobs\Connection;
use Faber\Core\Contracts\Jobs\Queue;
use Faber\Core\DI\Container;

class PendingDispatch
{
    public function __construct(protected Queue $job)
    {
    }

    public function onQueue(string $queue)
    {
        $this->job->onQueue($queue);

        return $this;
    }

    public function onAttempts(int $attempts)
    {
        $this->job->onAttempts($attempts);

        return $this;
    }

    public function send(): void
    {
        Container::getInstance()->get(Connection::class)->setQueue($this->job);
    }
}