<?php

namespace Faber\Core\Request;

use Faber\Core\Response\Response;

abstract class RequestForm extends Request
{
    public function __construct(
        protected Response $response
    )
    {
        parent::__construct();

        if (!$this->validate($this->all(), $this->rules(), $this->messages())) {
            $this->response->redirectTo($this->session()->previous());
        }
    }

    abstract public function rules(): array;

    abstract public function messages(): array;
}