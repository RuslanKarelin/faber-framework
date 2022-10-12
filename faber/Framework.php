<?php

namespace Faber;

use Faber\Core\Application;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Exceptions\Handler;

class Framework
{
    public function run(): void
    {
        try {
            (new Application())->handle();
        } catch (\Exception $exception) {
            Container::getInstance()->get(Reflection::class)->createObject(Handler::class)->render($exception);
        }
    }
}