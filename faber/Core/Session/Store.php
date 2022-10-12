<?php

namespace Faber\Core\Session;

use SessionHandlerInterface;
use Faber\Core\Helpers\Str;
use Faber\Core\Enums\Session as SessionEnum;

class Store
{
    public const ID_LENGTH = 40;
    public const SESSION_NAME = 'faber_sess_id';
    protected ?string $id;
    protected string $name;
    protected array $attributes = [];
    protected SessionHandlerInterface $handler;

    public function __construct($name, SessionHandlerInterface $handler, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->handler = $handler;

    }

    protected function isIdValid(string|null $id): bool
    {
        return is_string($id) && mb_strlen($id) === static::ID_LENGTH;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $this->isIdValid($id) ? $id : $this->generateSessionId();
    }

    public function generateSessionId(): string
    {
        return Str::random(static::ID_LENGTH);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->attributes) ? $this->attributes[$key] : $default;
    }

    public function set(string $key, string|array $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function delete(string $key): bool
    {
        if (array_key_exists($key, $this->attributes)) {
            unset($this->attributes[$key]);
            return true;
        }
        return false;
    }

    public function previous(): ?string
    {
        return $this->get(SessionEnum::PREVIOUS, '/');
    }

    public function flash(string $key, string $value): void
    {
        if (!array_key_exists(SessionEnum::FLASH, $this->attributes)) {
            $this->attributes[SessionEnum::FLASH] = [];
        }
        $this->attributes[SessionEnum::FLASH][$key] = $value;
    }

    public function error(string $key, string $value, string $rule = ''): void
    {
        if (!array_key_exists(SessionEnum::ERRORS, $this->attributes)) {
            $this->attributes[SessionEnum::ERRORS] = [];
        }
        if (!array_key_exists($key, $this->attributes[SessionEnum::ERRORS])) $this->attributes[SessionEnum::ERRORS][$key] = [];
        $rule ? $this->attributes[SessionEnum::ERRORS][$key][$rule] = $value : $this->attributes[SessionEnum::ERRORS][$key][] = $value;
    }

    public function write(): void
    {
        $this->handler->write($this->getId(), serialize($this->getAttributes()));
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function token(): ?string
    {
        return $this->get(SessionEnum::TOKEN);
    }

    public function generateToken(): void
    {
        $this->set(SessionEnum::TOKEN, Str::random(static::ID_LENGTH));
    }
}