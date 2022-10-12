<?php

namespace Faber\Core\Mail\Transport;

use Swift_Events_EventListener;
use Swift_Transport;

abstract class Transport implements Swift_Transport
{
    public array $plugins = [];

    public function isStarted()
    {
        return true;
    }

    public function start()
    {
        return true;
    }

    public function stop()
    {
        return true;
    }

    public function ping()
    {
        return true;
    }

    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        array_push($this->plugins, $plugin);
    }
}