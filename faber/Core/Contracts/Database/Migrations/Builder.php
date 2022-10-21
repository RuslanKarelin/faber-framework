<?php

namespace Faber\Core\Contracts\Database\Migrations;

use Faber\Core\Database\Migrations\Builder\Columns\IntegerColumn;
use Faber\Core\Database\Migrations\Builder\Columns\StringColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TextColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TimestampColumn;

interface Builder
{
    public function table(string $table): void;
    public function string(string $column, int $length = 255): StringColumn;
    public function integer(string $column): IntegerColumn;
    public function id(string $column = 'id');
    public function text(string $column): TextColumn;
    public function timestamp(string $column): TimestampColumn;
    public function timestamps();
    public function createTable(): void;
    public function updateTable(): void;
}