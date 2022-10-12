<?php

namespace Faber\Core\Contracts\Validator;

use Faber\Core\Request\Request;
use Faber\Core\Utils\MessageBag;

interface Validator
{
    public function make(array $input, array $rules, array $messages = []): static;

    public function validate(): bool;

    public function errors(): MessageBag;
}