<?php

namespace Faber\Core\Jobs\Traits;

use Faber\Core\Jobs\PendingDispatch;

trait Dispatchable
{
    public static function dispatch(...$arguments)
    {
        return new PendingDispatch(new static(...$arguments));
    }
}