<?php
return [
    'locale' => env('APP_LANG', 'en'),

    'providers' => [
        Faber\Core\Providers\ReflectionServiceProvider::class,
        Faber\Core\Providers\LangServiceProvider::class,
        Faber\Core\Providers\TemplateServiceProvider::class,
        Faber\Core\Providers\PaginatorServiceProvider::class,
        Faber\Core\Providers\ValidatorServiceProvider::class,
        Faber\Core\Providers\FilesystemServiceProvider::class,
        Faber\Core\Providers\LogServiceProvider::class,
        Faber\Core\Providers\QueryBuilderServiceProvider::class,
        Faber\Core\Providers\HashServiceProvider::class,
        Faber\Core\Providers\RouteServiceProvider::class,
        Faber\Core\Providers\RequestServiceProvider::class,
        Faber\Core\Providers\ResponseServiceProvider::class,
        Faber\Core\Providers\CookieServiceProvider::class,
        Faber\Core\Providers\AuthServiceProvider::class,
        Faber\Core\Providers\SessionServiceProvider::class,
        Faber\Core\Providers\DatabaseServiceProvider::class,
        Faber\Core\Providers\MigrationBuilderServiceProvider::class,
        Faber\Core\Providers\MailServiceProvider::class,
        Faber\Core\Providers\FacadeServiceProvider::class,
        App\Providers\AppServiceProvider::class,
    ],

    'middleware' => [
        Faber\Core\Middleware\Route::class,
        Faber\Core\Middleware\StartSession::class,
        Faber\Core\Middleware\CSRF::class
    ],

    'routeMiddleware' => [
        'auth' => App\Middleware\Auth::class,
    ],

    'afterMiddleware' => [
        Faber\Core\Middleware\Database::class,
        Faber\Core\Middleware\EndSession::class,
    ],

    'resourcesPath' => [
        'app' => '/resources/views/',
        'faber' => '/faber/Core/resources/views/'
    ]
];