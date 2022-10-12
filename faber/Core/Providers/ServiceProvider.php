<?php

namespace Faber\Core\Providers;

use Faber\Core\Config\Config;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;

class ServiceProvider
{
    protected Reflection $reflection;
    protected Container $container;

    public function __construct()
    {
        $this->reflection = new Reflection();
        $this->container = Container::getInstance();
    }

    public function handle(): void
    {
        $config = $this->reflection->createObject(Config::class);
        $config->init();
        $this->container->singleton(Config::class, $config);

        if ($providers = config('app.providers')) {
            foreach ($providers as $provider) {
                (new $provider)->register();
            }
        }
    }
}