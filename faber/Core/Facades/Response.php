<?php

namespace Faber\Core\Facades;

use Faber\Core\Response\Response as CResponse;

/**
 * @method static CResponse setStatus(int $statusCode)
 * @method static CResponse view(string $path, array $params = [], ?string $folderPath = null)
 * @method static CResponse redirectTo(string $path, array $params = [])
 * @method static CResponse redirectToRoute(string $name, array $routeParams = [], array $params = [])
 * @method static CResponse headerNoCache()
 * @method static CResponse headerContentTypeJson()
 * @method static CResponse header(string $key, string $value)
 * @method static CResponse headers(array $data)
 * @method static false|string json(array $data)
 *
 * @see CResponse
 */
class Response extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Response";
    }
}