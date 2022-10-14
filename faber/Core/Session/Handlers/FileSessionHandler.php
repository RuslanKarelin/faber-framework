<?php

namespace Faber\Core\Session\Handlers;

use SessionHandlerInterface;
use Faber\Core\Contracts\Session\SessionHandler;
use Faber\Core\Cookie\Cookie;
use Faber\Core\DI\Container;
use Faber\Core\Contracts\Filesystem\Filesystem as IFilesystem;
use Faber\Core\DI\Reflection;
use Faber\Core\Filesystem\Finder;
use Faber\Core\Session\Store as SessionStore;

class FileSessionHandler implements SessionHandlerInterface, SessionHandler
{
    protected IFilesystem $filesystem;
    protected Cookie $cookie;
    protected SessionStore $sessionStore;

    public function __construct()
    {
        $this->filesystem = Container::getInstance()->get(Reflection::class)->createObject(IFilesystem::class);
        $this->cookie = Container::getInstance()->get(Reflection::class)->createObject(Cookie::class);
    }

    public function close(): bool
    {
        return true;
    }

    public function destroy(string $id): bool
    {
        $this->filesystem->delete(config('session.files') . '/' . $id);
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $sessionId = $this->sessionStore->getId();
        $files = (new Finder())->path(config('session.files'))
            ->ignoreFilesWithTheDotPrefix()
            ->files()
            ->subDate($max_lifetime)
            ->getFiles();
        foreach ($files as $id) {
            if ($sessionId === $id) {
                $this->cookie->delete(SessionStore::SESSION_NAME, '/');
                $this->sessionStore->setId(null);
            }
            $this->filesystem->delete(config('session.files') . '/' . $id);
        }
        return false;
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        return $this->filesystem->read(config('session.files') . '/' . $id);
    }

    public function write(string $id, string $data): bool
    {
        return $this->filesystem->put(config('session.files') . '/' . $id, $data);
    }

    public function setStore(SessionStore $sessionStore): void
    {
        $this->sessionStore = $sessionStore;
    }
}