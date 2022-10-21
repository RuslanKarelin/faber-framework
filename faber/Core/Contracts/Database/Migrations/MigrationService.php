<?php

namespace Faber\Core\Contracts\Database\Migrations;

interface MigrationService
{
    public function query(string $query): array|null;
    public function exec(string $query): void;
}