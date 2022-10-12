<?php

namespace Faber\Core\Exceptions;

use Faber\Core\Response\Response;

abstract class ExceptionHandler
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    abstract public function render(\Exception $exception): void;
}