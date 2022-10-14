<?php

namespace Faber\Core\Lang;

use Faber\Core\Filesystem\Finder;

class Lang
{
    const LANG_DIRECTORY = '../lang';

    protected static array $lang = [];

    public function init(): void
    {
        $directoryList = (new Finder())->path(static::LANG_DIRECTORY)->directories()->getDirectories();
        foreach ($directoryList as $directory) {
            static::$lang[$directory] = [];
            $fileList = (new Finder())->path(static::LANG_DIRECTORY . '/' . $directory)->recursive()->getFiles();
            foreach ($fileList as $fileName) {
                $pathDirname = pathinfo($fileName, PATHINFO_DIRNAME);
                $currentLangDirectory = '/' . $directory . '/';
                if (str_contains($pathDirname, $currentLangDirectory)) {
                    $pathDirname = ltrim(
                        substr($pathDirname, strpos($pathDirname, $currentLangDirectory)),
                        $currentLangDirectory
                    );
                    $nested_array = [];
                    $temp = &$nested_array;
                    foreach (explode('/', $pathDirname) as $key) {
                        $temp = &$temp[$key];
                    }
                    $temp = [pathinfo($fileName, PATHINFO_FILENAME) => include $fileName];
                    static::$lang[$directory] = $nested_array;
                } else {
                    static::$lang[$directory][pathinfo($fileName, PATHINFO_FILENAME)] = include $fileName;
                }
            }
        }
    }

    public function getLang(): array
    {
        return self::$lang;
    }
}