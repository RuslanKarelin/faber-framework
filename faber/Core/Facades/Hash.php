<?php

namespace Faber\Core\Facades;

use Faber\Core\Contracts\Hash\Hash as IHash;

/**
 * @method string make(string $value, array $options = [])
 * @method bool check(string $value, string $hashed)
 *
 * @see IHash
 */
class Hash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Hash";
    }
}