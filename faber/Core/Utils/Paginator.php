<?php

namespace Faber\Core\Utils;

use ArrayIterator;
use IteratorAggregate;
use Faber\Core\Contracts\Database\Paginator as IPaginator;
use Faber\Core\DI\Container;
use Faber\Core\Request\Request;
use Faber\Core\Response\Response;
use Faber\Core\Route\Route;

class Paginator implements IteratorAggregate, IPaginator
{
    protected Collection $items;
    protected Request $request;
    protected Route $route;
    protected Response $response;
    protected ?int $prevPage = null;
    protected ?int $nextPage = null;
    protected ?int $lastPage = null;
    protected int $currentPage = 1;
    protected int $firstPage = 1;
    protected int $perPage = 0;
    protected int $itemsCount = 0;
    protected int $totalPage = 0;
    protected array $elements = [];
    protected array $with = [];
    protected int $countLinks = 6;

    protected function init(Collection $items, int $count, int $perPage)
    {
        $this->items = $items;
        $this->perPage = $perPage;
        $this->itemsCount = $count;

        $this->totalPage = ceil($this->itemsCount / $this->perPage);
        $this->currentPage = $this->request->get('page', 1);
        $this->lastPage = $this->totalPage;

        if ($this->totalPage > 1) {
            $this->nextPage = (($this->currentPage + 1) > $this->totalPage) ? null : $this->currentPage + 1;
        }

        if ($this->currentPage > 2) {
            $this->prevPage = $this->currentPage - 1;
        }

        $this->setElements();
    }

    protected function setElements(): void
    {
        $this->elements = [];
        $countLinks = ceil($this->countLinks / 2);

        $start = $this->currentPage - $countLinks;
        if ($start < $countLinks) $start = 2;

        $end = $this->currentPage + $countLinks + 1;
        if ($end > $this->totalPage) $end = $this->totalPage;

        for ($i = $start; $i < $end; $i++) {
            $this->elements[] = [
                'url' => route(
                        $this->route->currentName(),
                        array_merge(
                            $this->route->currentParams(),
                            $i != 1 ? $this->setQueryParamsWithPage($i) : $this->setQueryParams()
                        )
                    ),
                'page' => $i
            ];
        }

        if ($start > $countLinks - 1)
            array_unshift($this->elements, ['url' => null, 'page' => '...']);

        if (($this->totalPage + $countLinks) - $end !== $countLinks)
            $this->elements[] = ['url' => null, 'page' => '...'];
    }

    protected function setQueryParamsWithPage(int $page): array
    {
        return array_merge(['page' => $page], $this->with ? $this->request->only($this->with) : []);
    }

    protected function setQueryParams(): array
    {
        return $this->with ? $this->request->only($this->with) : [];
    }

    protected function getElements(): array
    {
        return $this->elements;
    }

    public function __construct()
    {
        $this->request = Container::getInstance()->get(Request::class);
        $this->route = Container::getInstance()->get(Route::class);
        $this->response = Container::getInstance()->get(Response::class);
    }

    public function lastPage(): ?int
    {
        return $this->lastPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function firstPage(): int
    {
        return $this->firstPage;
    }

    public function totalPage(): int
    {
        return $this->totalPage;
    }

    public function getOffset(int $perPage): int
    {
        return ($this->request->get('page', 1) - 1) * $perPage;
    }

    public function get(Collection $items, int $count, int $perPage): static
    {
        $this->init($items, $count, $perPage);
        return $this;
    }

    public function getIterator(): ArrayIterator
    {
        return $this->items->getIterator();
    }

    public function hasPages(): bool
    {
        return boolval($this->totalPage);
    }

    public function onFirstPage(): bool
    {
        return $this->firstPage === $this->request->get('page', 1);
    }

    public function previousPageUrl(): string
    {
        return route(
                $this->route->currentName(),
                array_merge(
                    $this->route->currentParams(),
                    $this->prevPage ? $this->setQueryParamsWithPage($this->prevPage) : $this->setQueryParams()
                )
            );
    }

    public function firstPageUrl(): string
    {
        return route(
                $this->route->currentName(),
                array_merge(
                    $this->route->currentParams(),
                    $this->setQueryParams()
                )
            );
    }

    public function lastPageUrl(): string
    {
        return route(
                $this->route->currentName(),
                array_merge(
                    $this->route->currentParams(),
                    $this->totalPage() ? $this->setQueryParamsWithPage($this->totalPage()) : $this->setQueryParams()
                )
            );
    }

    public function nextPageUrl(): string
    {
        return route(
                $this->route->currentName(),
                array_merge(
                    $this->route->currentParams(),
                    $this->nextPage ? $this->setQueryParamsWithPage($this->nextPage) : $this->setQueryParams()
                )
            );
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->totalPage;
    }

    public function isNotEmpty(): bool
    {
        return $this->items->isNotEmpty();
    }

    public function with(array $params = []): static
    {
        $this->with = $params;
        $this->setElements();
        return $this;
    }

    public function countLinks(int $countLinks): static
    {
        $this->countLinks = $countLinks;
        $this->setElements();
        return $this;
    }

    public function links(?string $view = null)
    {
        $resourcesPath = config('app.resourcesPath');
        $folderPath = null;
        if (!$view) {
            $folderPath = $resourcesPath['faber'];
            $view = 'pagination.pagination';
        }
        $this->response->view(
            $view,
            [
                'paginator' => $this,
                'elements' => $this->getElements()
            ],
            $folderPath
        );
    }
}