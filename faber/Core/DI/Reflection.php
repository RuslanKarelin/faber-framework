<?php

namespace Faber\Core\DI;

use Faber\Core\Exceptions\ReflectionException;
use Faber\Core\Route\Route;

class Reflection
{
    public function createObject($className): mixed
    {
        if (array_key_exists($className, Container::getBindings())) {
            $className = Container::getBindings()[$className];
        }

        if (array_key_exists($className, Container::getInstances())) {
            return Container::getInstances()[$className];
        }

        $reflectionClass = new \ReflectionClass($className);
        if ($constructor = $reflectionClass->getConstructor()) {
            $parametrs = $constructor->getParameters();
            $params = [];
            foreach ($parametrs as $parametr) {
                $params[] = $this->createObject($parametr->getType()->getName());
            }
            return $reflectionClass->newInstanceArgs($params);
        }

        return $reflectionClass->newInstanceWithoutConstructor();
    }

    public function handleMethod(mixed $object, string $methodName): mixed
    {
        $reflectionObject = new \ReflectionObject($object);
        if ($reflectionObject->hasMethod($methodName)) {
            $routeParams = ((Container::getInstance())->get(Route::class))->getParams();
            $method = $reflectionObject->getMethod($methodName);
            if ($parametrs = $method->getParameters()) {
                $params = [];
                foreach ($parametrs as $parametr) {
                    $parametrName = $parametr->getName();
                    if (array_key_exists($parametrName, $routeParams)) {
                        $params[] = $routeParams[$parametrName];
                    } else {
                        $params[] = $this->createObject($parametr->getType()->getName());
                    }
                }
                return $method->invokeArgs($object, $params);
            } else {
                return $method->invoke($object);
            }
        }
        throw new ReflectionException();
    }

    public function callClosure(\Closure $closure): mixed
    {
        $reflectionClosure = new \ReflectionFunction($closure);
        if ($parametrs = $reflectionClosure->getParameters()) {
            $params = [];
            foreach ($parametrs as $parametr) {
                $params[] = $this->createObject($parametr->getType()->getName());
            }
            return $reflectionClosure->invokeArgs($params);
        } else {
            return $reflectionClosure->invoke();
        }
    }

    public function init(mixed $action): mixed
    {
        if (is_array($action) && count($action) === 2) {
            $controller = $this->createObject($action[0]);
            return $this->handleMethod($controller, $action[1]);
        } elseif ($action instanceof \Closure) {
            return $this->callClosure($action);
        }
        throw new ReflectionException();
    }
}