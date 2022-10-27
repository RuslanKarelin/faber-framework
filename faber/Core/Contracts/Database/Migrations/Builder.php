<?php

namespace Faber\Core\Contracts\Database\Migrations;

use Faber\Core\Database\Migrations\Builder\Columns\BigIntegerColumn;
use Faber\Core\Database\Migrations\Builder\Columns\IntegerColumn;
use Faber\Core\Database\Migrations\Builder\Columns\StringColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TextColumn;
use Faber\Core\Database\Migrations\Builder\Columns\TimestampColumn;
use Faber\Core\Database\Migrations\Builder\Foreign\Foreign;

interface Builder
{
    public function table(string $table): void;

    public function string(string $column, int $length = 255): StringColumn;

    public function bigInteger(string $column): BigIntegerColumn;

    public function integer(string $column): IntegerColumn;

    public function id(string $column = 'id');

    public function text(string $column): TextColumn;

    public function timestamp(string $column): TimestampColumn;

    public function timestamps();

    public function createTable(): void;

    public function updateTable(): void;

    public function foreign(string $column): Foreign;

    public function dropColumn(string|array $column): void;

    public function primary(string $index): void;

    public function unique(string $index): void;

    public function index(string $index): void;

    public function fullText(string $index): void;

    public function spatialIndex(string $index): void;

    public function dropForeign(string|array $index): void;

    public function dropPrimary(string $index): void;

    public function dropUnique(string $index): void;

    public function dropIndex(string $index): void;

    public function dropFullText(string $index): void;

    public function dropSpatialIndex(string $index): void;
}