<?php

namespace Faber\Core\Config;

use Faber\Core\Filesystem\Finder;

class Config
{
    const CONFIG_DIRECTORY = 'config';
    protected static array $config = [];

    public function init(): void
    {
        $configDirectory = root_path(static::CONFIG_DIRECTORY);
        $fileList = (new Finder())->path($configDirectory)->files()->getFiles();
        foreach ($fileList as $file) {
            $fileNameArray = explode('.', $file);
            $fileName = array_shift($fileNameArray);
            static::$config[$fileName] = include $configDirectory . '/' . $file;
        }
    }

    public function getConfig(): array
    {
        return self::$config;
    }
}