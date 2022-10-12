<?php

namespace Faber\Core\Response;

class Redirector
{
    public function redirect(string $url, int $status = 301, bool $replace = true): void
    {
        header('Location: ' . $url, $replace, $status);
        exit;
    }
}