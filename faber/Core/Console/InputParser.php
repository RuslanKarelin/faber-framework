<?php

namespace Faber\Core\Console;

class InputParser
{
    protected static string $name = '';
    protected static array $arguments = [];

    protected static function setName(array $argv)
    {
        if (!empty($argv[1])) static::$name = $argv[1];
    }

    protected static function setArguments(array $argv)
    {
        if (count($argv) > 2) {
            $argv = array_slice($argv, 2);
            foreach ($argv as $arg) {
                $arg = ltrim($arg, '-');
                if (str_contains($arg, '=')) {
                    $argArray = explode('=', $arg);
                    static::$arguments[$argArray[0]] = $argArray[1];
                } else {
                    static::$arguments[] = $arg;
                }
            }
        }
    }

    public static function parse(array $argv): array
    {
        static::setName($argv);
        static::setArguments($argv);
        return [static::$name, static::$arguments];
    }
}