<?php

namespace Faber\Core\Middleware;

use SessionHandlerInterface;
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
    }

    public function handle(Request $request, Closure $next)
    {
        $this->handler->gc(config('session.lifetime'));
        $sessionId = $request->cookie()->get(Store::SESSION_NAME);
        if ($sessionId && $this->handler->hasById($sessionId)) {
            $this->sessionStore->setId($sessionId);
            if ($data = $this->handler->read($sessionId)) {
                $this->sessionStore->setAttributes(unserialize($data));
            }
        } else {
            $this->sessionStore->regenerate();
        }
        return $next($request);
    }
}