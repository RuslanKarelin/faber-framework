<?php

namespace Faber\Core\Console;

use Faber\Core\Helpers\Arr;

class SignatureParse
{
    public static function parse(string $signature): array
    {
        $optionalParameters = [];
        $signatureArray = Arr::notNullValues(explode(' ', $signature));
        foreach ($signatureArray as &$signatureValue) {
            $signatureValue = ltrim(str_replace(['{', '}'], ['', ''], $signatureValue), '-');
            if (str_ends_with($signatureValue, '?')) {
                $signatureValue = rtrim($signatureValue, '?');
                $optionalParameters[] = $signatureValue;
            }
        }
        return [$signatureArray[0], array_slice($signatureArray, 1), $optionalParameters];
    }
}