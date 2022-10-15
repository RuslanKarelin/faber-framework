<?php

namespace Faber\Core\Filesystem;

use Faber\Core\Contracts\Filesystem\Filesystem as IFilesystem;

class Filesystem implements IFilesystem
{
    protected string $path = '';
    protected bool $isDiskSet = false;

    protected function setPath(string $path): string
    {
        if ($this->isDiskSet) $path = $this->path . $path;
        return $path;
    }

    protected function mkdir(string $path): void
    {
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathString = '';
        foreach ($pathArray as $item) {
            $pathString .= $item . '/';
            if (!file_exists($pathString)) {
                mkdir($pathString, 0755);
            }
        }
    }

    public function __construct()
    {
        $this->path = storage_path();
    }

    public function put(string $path, string $data): bool
    {
        $path = $this->setPath($path);
        $this->mkdir($path);
        if (boolval(file_put_contents($path, $data))) return true;
        return false;
    }

    public function append(string $path, string $data): bool
    {
        $path = $this->setPath($path);
        $this->mkdir($path);
        if (!file_exists($path))
            file_put_contents($path, '');
        if (boolval(file_put_contents($path, $data . PHP_EOL, FILE_APPEND | LOCK_EX)))
            return true;
        return false;
    }

    public function disk(string $name): static
    {
        $this->isDiskSet = true;
        $this->path .= 'app/' . $name . '/';
        $this->mkdir($this->path);
        return $this;
    }

    public function delete(string $path)
    {
        $path = $this->setPath($path);
        if (is_file($path)) unlink($path);
        else rmdir($path);
    }

    public function read(string $path): string|false
    {
        $path = $this->setPath($path);
        if (!file_exists($path)) return false;
        return file_get_contents($path);
    }

    public function upload(array $data): ?string
    {
        $this->disk('public');
        $relativePath = '/uploads/' . date('Y') . '/' . basename(time() . '_' . $data['name']);
        $this->mkdir($this->path . $relativePath);
        if (move_uploaded_file($data['tmp_name'], $this->path . $relativePath)) {
            return '/storage' . $relativePath;
        }
        return null;
    }

    public function get(string $path): string
    {
        $path = ltrim($path, '/storage');
        return $this->path . $path;
    }

    public function exist(string $path): bool
    {
        return file_exists($path);
    }
}