<?php

namespace Faber\Core\Middleware;

use Faber\Core\Request\Request;
use Closure;
use Faber\Core\Enums\Session as SessionEnum;
use Faber\Core\Exceptions\CSRFException;

class CSRF
{
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->isUnsafeMethod() &&
            $request->get(SessionEnum::TOKEN) !== $request->session()->token() &&
            !str_starts_with(route()->currentType(), 'api')
        ) {
            throw new CSRFException('The token is not valid');
        }
        return $next($request);
    }
}