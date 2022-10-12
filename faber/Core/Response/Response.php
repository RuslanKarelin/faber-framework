<?php

namespace Faber\Core\Response;

use Faber\Core\Contracts\TemplateEngine\ITemplateEngine;
use Faber\Core\Exceptions\TemplateRender;
use Faber\Core\Request\Request;
use Faber\Core\Route\Route;
use Faber\Core\Utils\MessageBag;

class Response
{
    protected ITemplateEngine $templateEngine;
    protected Redirector $redirector;
    protected Request $request;
    protected Route $route;

    public function __construct(
        Request         $request,
        Route           $route,
        ITemplateEngine $templateEngine,
        Redirector      $redirector
    )
    {
        $this->templateEngine = $templateEngine->init();
        $this->redirector = $redirector;
        $this->request = $request;
        $this->route = $route;
    }

    public function setStatus(int $statusCode): static
    {
        http_response_code($statusCode);
        return $this;
    }

    public function render(string $path, array $params = [], ?string $folderPath = null): string
    {
        $resourcesPath = config('app.resourcesPath');
        $path = str_replace('.', '/', $path);
        if (!$folderPath) $folderPath = $resourcesPath['app'];
        if (file_exists(root_path() . $folderPath . $path . $this->templateEngine->getFileExtension())) {
            return $this->templateEngine->render($path . $this->templateEngine->getFileExtension(), $params);
        }
        throw new TemplateRender('Template not found: ' . $path);
    }

    /**
     * @throws TemplateRender
     */
    public function view(string $path, array $params = [], ?string $folderPath = null): static
    {
        $params['errors'] = new MessageBag($this->request->session()->get('_errors') ?? []);
        $params['messages'] = new MessageBag($this->request->session()->get('_flash') ?? []);
        echo $this->render($path, $params, $folderPath);
        return $this;
    }

    public function redirectTo(string $path, array $params = []): static
    {
        if (!empty($params['status'])) {
            $this->redirector->redirect($path, $params['status']);
            return $this;
        }
        $this->headerNoCache();
        $this->redirector->redirect($path);
        return $this;
    }

    public function redirectToRoute(string $name, array $routeParams = [], array $params = []): static
    {
        $path = $this->route->getUrlByRouteName($name, $routeParams);
        if (!empty($params['status'])) {
            $this->redirector->redirect($path, $params['status']);
            return $this;
        }
        $this->headerNoCache();
        $this->redirector->redirect($path);
        return $this;
    }

    public function headerNoCache(): static
    {
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        return $this;
    }

    public function headerContentTypeJson(): static
    {
        header('Content-type: application/json');
        return $this;
    }

    public function header(string $key, string $value): static
    {
        header("{$key}: {$value}");
        return $this;
    }

    public function headers(array $data): static
    {
        foreach ($data as $key => $value) {
            header("{$key}: {$value}");
        }
        return $this;
    }

    public function json(array $data): false|string
    {
        $this->headerContentTypeJson();
        return json_encode($data);
    }
}