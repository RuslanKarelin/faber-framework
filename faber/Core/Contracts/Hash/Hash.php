<?php

namespace Faber\Core\Contracts\Hash;

interface Hash
{
    public function make(string $value, array $options = []): string;
    public function check(string $value, string $hashed): bool;
}