<?php

namespace Faber\Core\Facades;

use Faber\Core\Contracts\Mail\Mail as IMail;

/**
 * @method IMail to(array $to)
 * @method IMail from(array $from)
 * @method IMail subject(string $subject)
 * @method IMail body(string $body)
 * @method IMail view(string $path, array $params = [], ?string $folderPath = null)
 * @method IMail attach(string|array $files)
 * @method void send()
 *
 * @see IMail
 */
class Mail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Mail";
    }
}