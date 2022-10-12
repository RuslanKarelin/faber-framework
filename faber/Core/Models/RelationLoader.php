<?php

namespace Faber\Core\Models;

use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Models\Relations\HasOne;
use Faber\Core\Utils\Collection;

class RelationLoader
{
    protected array $eagerLoad = [];
    protected array $relations = [];
    protected ?Model $model = null;
    protected string $className;
    protected ?Collection $collection = null;
    public const ORDER_RELATIONS_KEY = 'orderRelations';

    protected function getRelationListParams(array $relations): void
    {
        foreach ($relations as $key => $relationName) {
            $relationNames = explode('.', $relationName);
            $firstKey = $relationNames[0];
            foreach ($relationNames as $relationName) {
                $this->prepareLoadRelations($relationName, $firstKey, $key);
            }
        }
    }

    protected function loadRelations(bool $isForCollection = false): void
    {
        if (!empty($this->eagerLoad)) {
            $loads = [];
            foreach ($this->eagerLoad as $relationName => $itemsArray) {
                foreach ($itemsArray as $key => $item) {
                    if ($key == static::ORDER_RELATIONS_KEY || array_key_exists($relationName, $loads)) continue;
                    if (count($itemsArray[static::ORDER_RELATIONS_KEY][$key]) == 1 && !$isForCollection) {
                        $this->relations[$relationName] = $item[$relationName]->getRelation();
                    } else {
                        $this->relations[$relationName] = $item[$relationName]->withRelation($relationName, $loads, $this->eagerLoad, $this->collection);
                    }
                }
            }
        }
    }

    protected function prepareLoadRelations(string $relationName, string $firstKey, int $key): void
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        if (method_exists($this->className, $relationName)) {
            $this->addToEagerLoad($this->model, $relationName, $key);
        } else {
            $lastKey = count($this->eagerLoad[$firstKey][static::ORDER_RELATIONS_KEY][$key]) - 1;
            $lastRelatedKey = $this->eagerLoad[$firstKey][static::ORDER_RELATIONS_KEY][$key][$lastKey];
            $this->addToEagerLoad(
                $reflection->createObject($this->eagerLoad[$firstKey][$key][$lastRelatedKey]->related),
                $relationName,
                $key,
                $firstKey
            );
        }
    }

    protected function addToEagerLoad(Model $model, string $relationName, int $key, ?string $firstKey = null): void
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        $firstKey = $firstKey ?? $relationName;
        $result = $reflection->handleMethod($model, $relationName);
        $this->eagerLoad[$firstKey][$key][$relationName] = $result;
        $this->eagerLoad[$firstKey][static::ORDER_RELATIONS_KEY][$key][] = $relationName;
    }

    protected function setRelationsToCollectionItems(Collection $collection, array $relations): array
    {
        $collection = $collection->toArray();
        foreach ($collection as $itemCollection) {
            foreach ($this->relations as $key => $relations) {
                foreach ($relations as $relation) {
                    $eagerLoadData = $this->eagerLoad[$key][array_key_first($this->eagerLoad[$key])][$key];
                    if ($relation->{$eagerLoadData->foreignKey} == $itemCollection->{$eagerLoadData->localKey}) {
                        if ($eagerLoadData instanceof HasOne && !empty($itemCollection->relations[$key])) continue;

                        if (!isset($itemCollection->relations[$key])) $itemCollection->relations[$key] = [];
                        $itemCollection->relations[$key][] = $relation;
                        if ($eagerLoadData instanceof HasOne) {
                            $itemCollection->relations[$key] = $itemCollection->relations[$key][0];
                        }
                    }
                }
            }
        }
        return $collection;
    }

    public function load(array $relations, string $className, ?Model $model = null): array
    {
        if (!empty($relations)) {
            $this->model = $model;
            $this->className = $className;
            $this->getRelationListParams($relations);
            $this->loadRelations();
        }
        $this->eagerLoad = [];
        return $this->relations;
    }

    public function with(array $relations, Collection $collection, string $className): Collection
    {
        if ($collection->isNotEmpty()) {
            $this->collection = $collection;
            $reflection = Container::getInstance()->get(Reflection::class);
            $this->className = $className;
            $this->model = $reflection->createObject($this->className);
            $this->getRelationListParams($relations);
            $this->loadRelations(true);
            $collection = $this->setRelationsToCollectionItems($collection, $relations);
            return new Collection($collection);
        }
        return $collection;
    }
}