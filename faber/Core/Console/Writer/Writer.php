<?php

namespace Faber\Core\Console\Writer;

use Faber\Core\Console\Writer\Enums\Foreground;

class Writer
{
    public static function writeColorMessage(string $message, string $color): void
    {
        echo "\e[" . $color . "m" . $message . "\e[0m\n";
    }

    public static function success(array|string $message): void
    {
        if (is_array($message)) {
            foreach ($message as $it) {
                static::writeColorMessage($it, Foreground::GREEN);
            }
        } else {
            static::writeColorMessage($message, Foreground::GREEN);
        }
    }

    public static function fail(array|string $message): void
    {
        if (is_array($message)) {
            foreach ($message as $it) {
                static::writeColorMessage($it, Foreground::RED);
            }
        } else {
            static::writeColorMessage($message, Foreground::RED);
        }
    }

    public static function table(array $data): void
    {
        $columns = [];
        foreach ($data as $rowKey => $row) {
            foreach ($row as $cellKey => $cell) {
                $length = strlen($cell);
                if (empty($columns[$cellKey]) || $columns[$cellKey] < $length) {
                    $columns[$cellKey] = $length;
                }
            }
        }

        $table = '';
        foreach ($data as $rowKey => $row) {
            foreach ($row as $cellKey => $cell)
                $table .= str_pad($cell, $columns[$cellKey]) . '   ';
            $table .= PHP_EOL;
        }
        echo "\e[" . Foreground::YELLOW . "m" . $table . "\e[0m\n";;
    }
}