<?php

namespace Faber\Core\Helpers;

class App
{
    protected static string $appLocale;

    public static function getLocale(): string
    {
        return static::$appLocale ?? config('app.locale', 'en');
    }

    public static function setLocale(string $locale): void
    {
        static::$appLocale = $locale;
    }
}