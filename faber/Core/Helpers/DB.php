<?php

namespace Faber\Core\Helpers;

class DB
{
    public static function getTableNameFromClassName(string $className, bool $isSingular = false): string
    {
        $tmpArray = explode('\\', $className);
        $className = array_pop($tmpArray);
        $tableName = strtolower(preg_replace(
            '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', '_', $className));

        if (!str_ends_with($tableName, 's') && !$isSingular) {
            $tableName .= 's';
        }

        return $tableName;
    }

    public static function getClassNameFromTableName(string $tableName): string
    {
        $tmpArray = explode('_', $tableName);
        if ($tmpArray) {
            $lastItemIndex = count($tmpArray) - 1;
            $tmpArray[$lastItemIndex] = rtrim($tmpArray[$lastItemIndex], 's');
        }

        $tmpArray = array_map(function ($it) {
            return ucfirst($it);
        }, $tmpArray);

        return 'App\\Models\\' . implode('', $tmpArray);
    }
}