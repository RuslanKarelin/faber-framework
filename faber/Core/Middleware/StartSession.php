<?php

namespace Faber\Core\Middleware;

use SessionHandlerInterface;
use Faber\Core\Filesystem\Finder;
use Faber\Core\Request\Request;
use Closure;
use Faber\Core\Session\Store;

class StartSession
{
    protected SessionHandlerInterface $handler;
    protected Store $sessionStore;

    public function __construct(SessionHandlerInterface $handler, Store $sessionStore)
    {
        $this->handler = $handler;
        $this->sessionStore = $sessionStore;
        $this->handler->setStore($this->sessionStore);
    }

    public function handle(Request $request, Closure $next)
    {
        if ($sessionId = $request->cookie()->get(Store::SESSION_NAME)) {
            $this->sessionStore->setId($sessionId);
            if ($data = $this->handler->read($sessionId)) {
                $this->sessionStore->setAttributes(unserialize($data));
            }
        } else {
            $this->sessionStore->setId($this->sessionStore->generateSessionId());
            $this->sessionStore->generateToken();
            $request->cookie()->set(Store::SESSION_NAME, $this->sessionStore->getId(), 0, '/');
            $this->sessionStore->write();
        }
        $this->handler->gc(config('session.lifetime'));
        return $next($request);
    }
}