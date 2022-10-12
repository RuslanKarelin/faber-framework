<?php

namespace Faber\Core\Helpers;

class Arr
{
    public static function flatten($items)
    {
        if (!is_array($items)) return [$items];
        return array_reduce($items, function ($carry, $item) {
            return array_merge($carry, static::flatten($item));
        }, []);
    }

    public static function notNullValues(array $array)
    {
        return array_values(array_filter($array, function($item) {
            if ($item) return true;
            return false;
        }));
    }
}