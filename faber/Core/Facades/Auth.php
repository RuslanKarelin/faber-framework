<?php

namespace Faber\Core\Facades;

use Faber\Core\Auth\Auth as CAuth;
use Faber\Core\Models\Model;

/**
 * @method void routes(array $options = [])
 * @method bool isAuth()
 * @method Model|null currentUser()
 *
 * @see CAuth
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Auth";
    }
}