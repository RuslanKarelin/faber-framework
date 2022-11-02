<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Validator\Validator as IValidator;
use Faber\Core\Validator\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(IValidator::class, Validator::class);
        $this->container->bind('Validator', IValidator::class);
    }
}