<?php

namespace Faber\Core\Helpers;

class Str
{
    public static function random(int $length): string
    {
        $data = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($data);
        return implode('', array_splice($data, 0, $length));
    }
}