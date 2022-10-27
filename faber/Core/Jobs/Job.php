<?php

namespace Faber\Core\Jobs;

use Faber\Core\Contracts\Jobs\Queue;

class Job
{
    public function __construct(
        protected int $id,
        protected string $payload,
        protected string $queue,
        protected int $attempts,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPayload(): Queue
    {
        return unserialize($this->payload);
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }
}