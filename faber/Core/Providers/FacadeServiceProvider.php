<?php

namespace Faber\Core\Providers;

use Faber\Core\Auth\Auth;
use Faber\Core\Contracts\Database\Builder;
use Faber\Core\Contracts\Filesystem\Filesystem;
use Faber\Core\Contracts\Hash\Hash;
use Faber\Core\Contracts\Log\Log;
use Faber\Core\Contracts\Mail\Mail;
use Faber\Core\Contracts\Validator\Validator;
use Faber\Core\Request\Request;
use Faber\Core\Response\Response;
use Faber\Core\Route\Route;
use Faber\Core\Session\Store as SessionStore;

class FacadeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind('Filesystem', Filesystem::class);
        $this->container->bind('Log', Log::class);
        $this->container->bind('Route', Route::class);
        $this->container->bind('Request', Request::class);
        $this->container->bind('Response', Response::class);
        $this->container->bind('DB', Builder::class);
        $this->container->bind('Session', SessionStore::class);
        $this->container->bind('Validator', Validator::class);
        $this->container->bind('Auth', Auth::class);
        $this->container->bind('Hash', Hash::class);
        $this->container->bind('Mail', Mail::class);
    }
}