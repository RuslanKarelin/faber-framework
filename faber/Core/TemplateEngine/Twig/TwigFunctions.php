<?php

namespace Faber\Core\TemplateEngine\Twig;

use Faber\Core\Auth\Auth;
use Faber\Core\DI\Container;
use Faber\Core\Enums\Session as SessionEnum;
use Faber\Core\Request\Request;
use Faber\Core\Route\Route;
use Twig\Environment;
use Twig\TwigFunction;

class TwigFunctions
{
    public function __construct(protected Environment $templateEngine)
    {
    }

    protected function addRoute(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('route', function (?string $name = null, array $params = []): string|Route {
                return route($name, $params);
            })
        );
        return $this;
    }

    protected function addApp(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('app', function (): Container {
                return app();
            })
        );
        return $this;
    }

    protected function addRequest(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('request', function (): Request {
                return request();
            })
        );
        return $this;
    }

    protected function addOld(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('old', function (string $fieldName): ?string {
                return old($fieldName);
            })
        );
        return $this;
    }

    protected function addAuth(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('auth', function (): Auth {
                return auth();
            })
        );
        return $this;
    }

    protected function addCsrfToken(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('csrf_token', function (): ?string {
                return csrf_token();
            })
        );
        return $this;
    }

    protected function addCsrf(): static
    {
        $this->templateEngine->addFunction(
            new TwigFunction('csrf', function (): void {
                echo '<input type="hidden" name="'.SessionEnum::TOKEN.'" value="'.csrf_token().'">';
            })
        );
        return $this;
    }

    public function addFunctions()
    {
        $this->addApp()
            ->addAuth()
            ->addCsrf()
            ->addCsrfToken()
            ->addOld()
            ->addRequest()
            ->addRoute();
    }
}