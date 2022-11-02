<?php

namespace Faber\Core\Providers;

use Faber\Core\Session\SessionFactory;
use Faber\Core\Session\Store as SessionStore;
use SessionHandlerInterface;

class SessionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $sessionHandler = SessionFactory::createHandler(config('session.driver'));
        $this->container->bind(SessionHandlerInterface::class, $sessionHandler::class);
        $this->container->singleton(SessionHandlerInterface::class, $sessionHandler);
        $this->container->singleton(SessionStore::class, new SessionStore(SessionStore::SESSION_NAME, $sessionHandler));
        $this->container->bind('Session', SessionStore::class);
    }
}