<?php

namespace Faber\Core\Facades;

use Faber\Core\Contracts\Validator\Validator as IValidator;

/**
 * @method static IValidator make(array $input, array $rules, array $messages = [])
 * @method static bool validate()
 *
 * @see IValidator
 */
class Validator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Validator";
    }
}