<?php

namespace Faber\Core\Validator;

use Faber\Core\DI\Container;
use Faber\Core\Facades\DB;
use Faber\Core\Request\Request;

class Rule
{
    public static function required(string $rule, mixed $value): bool
    {
        return !empty($value);
    }

    public static function string(string $rule, mixed $value): bool
    {
        return is_string($value);
    }

    public static function size(string $rule, mixed $value): bool
    {
        $ruleArray = explode('|', $rule);
        $size = explode(':', $ruleArray[count($ruleArray) - 1]);
        $count = count($ruleArray);
        return match (true) {
            empty($value) => false,
            $count == 1 => mb_strlen($value) == $size[1],
            $count == 2 && $ruleArray[0] == 'integer' => $value == $size[1],
            $count == 2 && $ruleArray[0] == 'array' => count($value) == $size[1],
            default => true,
        };
    }

    public static function max(string $rule, mixed $value): bool
    {
        $ruleArray = explode(':', $rule);
        return mb_strlen($value) <= $ruleArray[1];
    }

    public static function min(string $rule, mixed $value): bool
    {
        $ruleArray = explode(':', $rule);
        return mb_strlen($value) >= $ruleArray[1];
    }

    public static function email(string $rule, mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function confirmed(string $rule, mixed $value, string $fieldName): bool
    {
        return $value == Container::getInstance()->get(Request::class)->get($fieldName . '_confirmation');
    }

    public static function unique(string $rule, mixed $value, string $fieldName): bool
    {
        $ruleArray = explode(':', $rule);
        $tableName = $ruleArray[1];
        if (DB::table($tableName)->where($fieldName, $value)->limit(1)->get()->isEmpty()) {
            return true;
        }
        return false;
    }
}