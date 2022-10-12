<?php

namespace Faber\Core\Hash;

use Faber\Core\Hash\Drivers\Bcrypt;

class HashFactory
{
    public static function getHashDriver(string $driverName): string
    {
        return match ($driverName) {
            default => Bcrypt::class,
        };
    }
}