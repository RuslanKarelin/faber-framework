<?php

namespace Faber\Core\Console;

use Faber\Core\Contracts\Filesystem\Filesystem;

class MakerFromStub
{
    public function __construct(protected Filesystem $filesystem)
    {
    }

    public function make(string $pathStub, string $pathTo, string $param): void
    {
        $data = $this->filesystem->read($pathStub);
        $pathArray = explode(DIRECTORY_SEPARATOR, $param);
        $class = array_pop($pathArray);
        $namespace = implode('\\', $pathArray);
        $namespace = $namespace ? '\\' . $namespace : '';
        $data = str_replace([':namespace', ':class'], [$namespace, $class], $data);
        $this->filesystem->put(root_path($pathTo . $param . '.php'), $data);
    }
}