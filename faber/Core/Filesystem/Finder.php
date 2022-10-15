<?php

namespace Faber\Core\Filesystem;

class Finder
{
    protected string $path;
    protected array $files = [];
    protected array $directories = [];
    protected bool $isIgnoringTheDotPrefix = false;

    public function path($path): static
    {
        $this->path = $path;
        return $this;
    }

    public function directories(): static
    {
        if (is_dir($this->path)) {
            $directoryList = scandir($this->path);
            foreach ($directoryList as $directory) {
                if (is_dir($this->path . '/' . $directory) && !in_array($directory, ['.', '..'])) {
                    $this->directories[] = $directory;
                }
            }
        }
        return $this;
    }

    public function files(): static
    {
        if (is_dir($this->path)) {
            $fileList = scandir($this->path);
            foreach ($fileList as $file) {
                if (!is_dir($this->path . '/' . $file)) {
                    if ($this->isIgnoringTheDotPrefix && str_starts_with($file, '.')) continue;
                    $this->files[] = $file;
                }
            }
        }
        return $this;
    }

    public function recursive(): static
    {
        $directory = new \RecursiveDirectoryIterator($this->path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $info) {
            if (!$info->isDir()) {
                $this->files[] = $info->getRealPath();
            }
        }
        return $this;
    }

    public function date($date, string $measureUnit = 'minutes'): static
    {
        return $this;
    }

    public function subDate(int $date, string $measureUnit = 'minutes'): static
    {
        $now = new \DateTime();
        $time = "+ {$date} {$measureUnit}";
        $tmpFiles = [];
        foreach ($this->files as $index => $file) {
            if ((strtotime($time, filemtime($this->path . '/' . $file)) - $now->getTimestamp()) <= 0) {
                $tmpFiles[] = $file;
            }
        }
        $this->files = $tmpFiles;
        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getFileNames(): array
    {
        $fileNames = [];
        foreach ($this->files as $file) {
            $fileNames[] = pathinfo($file, PATHINFO_FILENAME);
        }
        return $fileNames;
    }

    public function getDirectories(): array
    {
        return $this->directories;
    }

    public function ignoreFilesWithTheDotPrefix(): static
    {
        $this->isIgnoringTheDotPrefix = true;
        return $this;
    }
}