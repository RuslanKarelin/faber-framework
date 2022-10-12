<?php

namespace Faber\Core\Facades;

use Faber\Core\Contracts\Log\Log as ILog;

/**
 * @method static info(string $data)
 * @method static error(string $data)
 * @method static warning(string $data)
 *
 *
 * @see ILog
 */
class Log extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Log";
    }
}