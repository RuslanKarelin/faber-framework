<?php

namespace Faber\Core\Console\Commands;

use Faber\Core\Console\Command;
use Faber\Core\Console\Writer\Writer;
use Faber\Core\Route\Route;

class RouteList extends Command
{
    protected static string $signature = 'route:list';
    protected static string $description = 'Show route list';

    public function handle(Route $route)
    {
        $routeList = $route->addRoutes()->getRoutes();
        $dataTable = [['Method', 'Pattern', 'Action', 'Name', 'Middleware', 'Type']];
        foreach ($routeList as $items) {
            foreach ($items as $item) {
                $dataTable[] = [
                    $item['method'],
                    $item['pattern'],
                    is_array($item['action']) ? implode('@', $item['action']) : 'Closure',
                    !empty($item['name']) ? $item['name'] : '',
                    !empty($item['middleware']) ? implode(', ', $item['middleware']) : '',
                    $item['type'],
                ];
            }
        }
        Writer::table($dataTable);
    }
}