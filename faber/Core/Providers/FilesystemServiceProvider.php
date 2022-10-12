<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Filesystem\Filesystem as IFilesystem;
use Faber\Core\Filesystem\Filesystem;

class FilesystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(IFilesystem::class, Filesystem::class);
    }
}