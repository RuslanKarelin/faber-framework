<?php

namespace Faber\Core;

use Dotenv\Dotenv;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Middleware\Middleware;
use Faber\Core\Providers\ServiceProvider;
use Faber\Core\Response\Response;
use Faber\Core\Route\Route;
use Faber\Core\Enums\Http;
require_once 'Helpers/Global.php';

class Application
{
    private Container $container;
    private ServiceProvider $serviceProvider;

    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->serviceProvider = new ServiceProvider();
    }

    public function handle(): void
    {
        Dotenv::createImmutable(root_path())->safeLoad();
        $this->serviceProvider->handle();
        Middleware::process();
        if ($currentRoute = $this->container->get(Route::class)->current()) {
            Middleware::routeProcess();
            echo $this->container->get(Reflection::class)->init($currentRoute['action']);
        } else {
            $this->container->get(Response::class)->setStatus(Http::NOT_FOUND)->view('errors.404');
        }
        Middleware::processAfter();
    }
}