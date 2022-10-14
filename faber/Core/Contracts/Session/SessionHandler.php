<?php

namespace Faber\Core\Contracts\Session;

interface SessionHandler
{
    public function hasById($id): bool;
}