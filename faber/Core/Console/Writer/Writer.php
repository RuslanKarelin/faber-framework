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
}