<?php

namespace Faber\Core\Contracts\Session;

use Faber\Core\Session\Store as SessionStore;

interface SessionHandler
{
    public function setStore(SessionStore $sessionStore): void;
}