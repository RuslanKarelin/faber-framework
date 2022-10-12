<?php

namespace Faber\Core\Contracts\Log;

interface Log
{
    public function info(string $data);

    public function error(string $data);

    public function warning(string $data);
}