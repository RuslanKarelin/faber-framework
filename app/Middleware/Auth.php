<?php

namespace App\Middleware;

use Faber\Core\Response\Response;
use Faber\Core\Request\Request;
use Closure;

class Auth
{
    public function __construct(protected Response $response)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if (!\Faber\Core\Facades\Auth::isAuth()) {
            $this->response->redirectTo($request->session()->previous());
        }
        return $next($request);
    }
}