<?php

namespace Faber\Core\Hash\Drivers;

use Faber\Core\Contracts\Hash\Hash;

class Bcrypt implements Hash
{
    protected int $rounds = 10;

    protected function cost(array $options = [])
    {
        $this->rounds = $this->rounds ?: config('hashing.bcrypt.rounds');
        return $options['rounds'] ?? $this->rounds;
    }

    public function make(string $value, array $options = []): string
    {
        return password_hash($value, PASSWORD_BCRYPT, [
            'cost' => $this->cost($options),
        ]);
    }

    public function check(string $value, string $hashed): bool
    {
        return password_verify($value, $hashed);
    }
}