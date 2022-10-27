<?php

namespace Faber\Core\Jobs\Traits;

trait Queueable
{
    protected string $queue = 'default';
    protected int $attempts = 3;

    public function onQueue(string $queue)
    {
        $this->queue = $queue;

        return $this;
    }

    public function onAttempts(int $attempts)
    {
        $this->attempts = $attempts;

        return $this;
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