<?php

namespace Faber\Core\Request;

use App\Models\User;
use Faber\Core\Contracts\Validator\Validator;
use Faber\Core\Cookie\Cookie;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Models\Model;
use Faber\Core\Session\Store as SessionStore;
use Faber\Core\Enums\Session as SessionEnum;

class Request
{
    protected Validator $validator;
    protected ?Model $user = null;
    protected array $attributes = [];
    protected array $files = [];
    protected array $unsafeMethods = [
        'post', 'put', 'delete', 'patch'
    ];
    public array $serverData;
    public string $method;
    public string $uri;
    public string $path;
    public string $query;

    protected function validate(array $input, array $rules, array $messages = []): bool
    {
        return $this->validator->make($input, $rules, $messages)->validate();
    }

    public function __construct()
    {
        $this->serverData = $_SERVER;
        $this->uri = $this->serverData['REQUEST_URI'] ?? '';
        $urlData = parse_url($this->uri);
        $this->method = mb_strtolower($this->serverData['REQUEST_METHOD']) ?? '';
        $this->query = $urlData['query'] ?? '';
        $this->path = $urlData['path'] ?? '';
        $this->attributes = array_merge($_GET, $_POST, $_FILES);
        $this->files = $_FILES;
        $this->validator = Container::getInstance()->get(Reflection::class)->createObject(Validator::class);
    }

    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    public function fullUrl(): string
    {
        if (env('APP_URL')) {
            return env('APP_URL') . $this->uri;
        }

        if (!empty($_SERVER['REQUEST_SCHEME']) && !empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $this->uri;
        }

        return $this->uri;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $array = explode('.', $key);
        $attributeValue = null;
        if (count($array) > 1) {
            $attributeValue = $this->attributes;
            foreach ($array as $key) {
                if (array_key_exists($key, $attributeValue)) {
                    $attributeValue = $attributeValue[$key];
                }
            }
        } else {
            if (array_key_exists($key, $this->attributes)) {
                $attributeValue = $this->attributes[$key];
            }
        }

        return !empty($attributeValue) ? $attributeValue : $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function all(): array
    {
        return $this->attributes;
    }

    public function files(?string $key = null): ?array
    {
        if ($key) {
            if (isset($this->files[$key])) {
                return $this->files[$key];
            }
            return null;
        }
        return $this->files;
    }

    public function exclude(array $keys = []): array
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, $keys)) {
                $attributes[$key] = $value;
            }
        }
        return $attributes;
    }

    public function only(array $keys = []): array
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $keys)) {
                $attributes[$key] = $value;
            }
        }
        return $attributes;
    }

    public function cookie(): Cookie
    {
        return Container::getInstance()->get(Cookie::class);
    }

    public function session(): SessionStore
    {
        return Container::getInstance()->get(SessionStore::class);
    }

    public function isAjax(): bool
    {
        return !empty($this->serverData['HTTP_X_REQUESTED_WITH']) &&
            strtolower($this->serverData['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function isPost(): bool
    {
        return $this->method === 'post';
    }

    public function user(): ?Model
    {
        $userId = $this->session()->get(SessionEnum::USER_ID);
        if ($userId && !$this->user) {
            $this->user = User::find($userId);
        }
        return $this->user;
    }

    public function isUnsafeMethod(): bool
    {
        return in_array($this->method, $this->unsafeMethods);
    }
}