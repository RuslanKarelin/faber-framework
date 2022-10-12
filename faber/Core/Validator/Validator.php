<?php

namespace Faber\Core\Validator;

use Faber\Core\Contracts\Validator\Validator as IValidator;
use Faber\Core\DI\Container;
use Faber\Core\Request\Request;
use Faber\Core\Utils\MessageBag;
use SessionHandlerInterface;

class Validator implements IValidator
{
    protected Request $request;
    protected array $input;
    protected array $rules;
    protected array $messages;
    protected array $validationResult;
    protected array $errors = [];
    protected bool $isValid = true;

    protected function runValidate()
    {
        $this->validationResult = [];
        foreach ($this->rules as $fieldName => $rules) {
            foreach ($rules as $rule) {
                if (!array_key_exists($fieldName, $this->validationResult)) $this->validationResult[$fieldName] = [];
                $this->validationResult[$fieldName][$rule] = $this->validateField($fieldName, $rule);
            }
        }
    }

    protected function validateField(string $fieldName, string $rule): bool
    {
        $value = $this->getFieldValueByName($fieldName);
        return match (true) {
            $rule == 'required' => Rule::required($rule, $value),
            $rule == 'string' => Rule::string($rule, $value),
            $rule == 'email' => Rule::email($rule, $value),
            $rule == 'confirmed' => Rule::confirmed($rule, $value, $fieldName),
            str_contains($rule, 'size:') => Rule::size($rule, $value),
            str_starts_with($rule, 'max:') => Rule::max($rule, $value),
            str_starts_with($rule, 'min:') => Rule::min($rule, $value),
            str_starts_with($rule, 'unique:') => Rule::unique($rule, $value, $fieldName),
            default => true,
        };
    }

    protected function getFieldValueByName(string $fieldName): mixed
    {
        $array = explode('.', $fieldName);
        $inputArray = $this->input;
        foreach ($array as $key) {
            if (array_key_exists($key, $inputArray)) {
                $inputArray = $inputArray[$key];
            } else {
                $inputArray = null;
                break;
            }
        }
        return $inputArray ?? null;
    }

    protected function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function make(array $input, array $rules, array $messages = []): static
    {
        $this->setRequest(request());
        $this->input = $input;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->runValidate();
        return $this;
    }

    public function validate(): bool
    {
        $this->runValidate();
        $sessionStore = $this->request->session();
        foreach ($this->validationResult as $fieldName => $rules) {
            foreach ($rules as $rule => $isValid) {
                if (!$isValid) {
                    $this->isValid = $isValid;
                    $message = array_key_exists($fieldName . '.' . $rule, $this->messages) ?
                        $this->messages[$fieldName . '.' . $rule] :
                        trans('validator.errors.default', ['attribute' => $fieldName, 'rule' => $rule]);
                    $sessionStore->error($fieldName, $message, $rule);
                    if (!array_key_exists($fieldName, $this->errors)) $this->errors[$fieldName] = [];
                    $this->errors[$fieldName][] = $message;
                }
            }
        }

        $sessionStore->set('_old', $this->request->all());
        $sessionStore->write();
        return $this->isValid;
    }

    public function errors(): MessageBag
    {
        return new MessageBag($this->errors);
    }
}