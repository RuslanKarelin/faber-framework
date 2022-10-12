<?php

namespace Faber\Core\Auth;

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use Faber\Core\Facades\Route;
use Faber\Core\Models\Model;
use Faber\Core\Request\Request;

class Auth
{
    public function __construct(protected Request $request)
    {
    }

    public function routes(array $options = []): void
    {
        if ($options['register'] ?? true) {
            Route::get('register', [RegisterController::class, 'registerForm'])->name('register');
            Route::post('register', [RegisterController::class, 'register']);
        }

        if ($options['login'] ?? true) {
            Route::get('login', [LoginController::class, 'loginForm'])->name('login');
            Route::post('login', [LoginController::class, 'login']);
        }

        if ($options['logout'] ?? true) {
            Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        }
    }

    public function isAuth(): bool
    {
        if ($this->request->user()) {
           return true;
        }
        return false;
    }

    public function currentUser(): ?Model
    {
        if ($user = $this->request->user()) {
           return $user;
        }
        return null;
    }
}