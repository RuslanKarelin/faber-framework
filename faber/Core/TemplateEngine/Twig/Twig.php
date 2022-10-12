<?php

namespace Faber\Core\TemplateEngine\Twig;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Faber\Core\Contracts\TemplateEngine\ITemplateEngine;

class Twig implements ITemplateEngine
{
    protected Environment $templateEngine;

    public function init(): ITemplateEngine
    {
        $loader = new FilesystemLoader(
            array_map(
                function ($path) {
                    return root_path() . $path;
                }, array_values(
                    config('app.resourcesPath')
                )
            )
        );
        $this->templateEngine = new Environment($loader, [
            //'cache' => root_path() . '/storage/cache',
            //'auto_reload' => env('APP_ENV') === 'local',
            'auto_reload' => true,
            'debug' => env('APP_ENV') === 'local'
        ]);

        $this->templateEngine->addExtension(new DebugExtension());

        (new TwigFunctions($this->templateEngine))->addFunctions();

        return $this;
    }

    public function render(string $name, array $params = []): string
    {
        return $this->templateEngine->render($name, $params);
    }

    public function getFileExtension(): string
    {
        return '.twig';
    }
}