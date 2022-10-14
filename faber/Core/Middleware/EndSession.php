<?php

namespace Faber\Core\Middleware;

use Faber\Core\Request\Request;
use Closure;
use Faber\Core\Session\Store;
use Faber\Core\Enums\Session as SessionEnum;

class EndSession
{
    public function __construct(protected Store $sessionStore)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $this->sessionStore->set(SessionEnum::PREVIOUS, $request->fullUrl());
        $this->sessionStore->set(SessionEnum::OLD, $request->all());
        $this->sessionStore->delete(SessionEnum::FLASH);
        $this->sessionStore->delete(SessionEnum::ERRORS);
        $this->sessionStore->write();

        return $next($request);
    }
}