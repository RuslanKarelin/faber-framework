<?php

namespace Faber\Core\Exceptions;


use Faber\Core\Facades\Log;

class Handler extends ExceptionHandler
{
    public function render(\Exception $exception): void
    {
        Log::error($exception::class . ' ' . $exception->getMessage());
        $this->response->setStatus($exception->getCode())->view(
            'exceptions.default',
            [
                'className' => $exception::class,
                'exception' => $exception
            ],
            config('app.resourcesPath.faber')
        );
    }
}