<?php

namespace Faber\Core\Console;

use Faber\Core\Helpers\Arr;

class SignatureParse
{
    public static function parse(string $signature): array
    {
        $signatureArray = Arr::notNullValues(explode(' ', $signature));
        foreach ($signatureArray as &$signatureValue) {
            $signatureValue = ltrim(str_replace(['{', '}'], ['', ''], $signatureValue), '-');
        }
        return [$signatureArray[0], array_slice($signatureArray, 1)];
    }
}