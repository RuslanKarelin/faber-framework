<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\TemplateEngine\ITemplateEngine;
use Faber\Core\TemplateEngine\Twig\Twig;

class TemplateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(ITemplateEngine::class, Twig::class);
    }
}