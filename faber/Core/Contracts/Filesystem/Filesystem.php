<?php

namespace Faber\Core\Contracts\Filesystem;

interface Filesystem
{
    public function put(string $path, string $data): bool;

    public function get(string $path): string;

    public function append(string $path, string $data): bool;

    public function disk(string $name): static;

    public function read(string $path): string|false;

    public function delete(string $path);
}