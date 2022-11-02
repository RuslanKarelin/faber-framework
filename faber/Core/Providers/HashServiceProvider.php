<?php

namespace Faber\Core\Providers;


use Faber\Core\Contracts\Hash\Hash as IHash;
use Faber\Core\Hash\HashFactory;

class HashServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(IHash::class, HashFactory::getHashDriver(config('hashing.driver')));
        $this->container->bind('Hash', IHash::class);
    }
}