<?php

namespace Faber\Core\Filesystem;

use Faber\Core\Contracts\Filesystem\Filesystem as IFilesystem;

class Filesystem implements IFilesystem
{
    protected string $path = '';

    public function __construct()
    {
        $this->path = storage_path();
    }

    public function put(string $path, string $data): bool
    {
        if (boolval(file_put_contents($this->path . '/' . $path, $data))) return true;
        return false;
    }

    public function append(string $path, string $data): bool
    {
        if (!file_exists($this->path . '/' . $path))
            file_put_contents($this->path . '/' . $path, '');
        if (boolval(file_put_contents($this->path . '/' . $path, $data . PHP_EOL, FILE_APPEND | LOCK_EX)))
            return true;
        return false;
    }

    public function disk(string $name): static
    {
        $this->path .= '/app/' . $name;
        if (!file_exists($this->path)) mkdir($this->path, 0755);
        return $this;
    }

    public function delete(string $path)
    {
        $path = $this->path . '/' . $path;
        if (is_file($path)) unlink($path);
        else rmdir($path);
    }

    public function read(string $path): string|false
    {
        if (!file_exists($this->path . '/' . $path)) return false;
        return file_get_contents($this->path . '/' . $path);
    }

    public function upload(array $data): ?string
    {
        $this->disk('public');
        $filePath = $this->path;

        $relativePath = '/uploads/';
        if (!file_exists($filePath . $relativePath))
            mkdir($filePath . $relativePath, 0755);

        $relativePath .= date('Y');
        if (!file_exists($filePath . $relativePath))
            mkdir($filePath . $relativePath, 0755);

        $relativePath .= '/' . basename(time() . '_' . $data['name']);

        if (move_uploaded_file($data['tmp_name'], $filePath . $relativePath)) {
            return '/storage' . $relativePath;
        }
        return null;
    }

    public function get(string $path): string
    {
        $path = str_replace('/storage/', '/', $path);
        return $this->path . $path;
    }
}