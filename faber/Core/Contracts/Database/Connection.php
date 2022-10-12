<?php

namespace Faber\Core\Contracts\Database;

interface Connection
{
    public function connection();
    public function close();
    public function query(string $query): array|null;
}