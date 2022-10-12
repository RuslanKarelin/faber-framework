<?php

namespace Faber\Core\Providers;

use Faber\Core\Lang\Lang;

class LangServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $lang = $this->reflection->createObject(Lang::class);
        $lang->init();
        $this->container->singleton(Lang::class, $lang);    }
}