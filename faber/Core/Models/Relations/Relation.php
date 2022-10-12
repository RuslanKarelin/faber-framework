<?php

namespace Faber\Core\Models\Relations;

use Faber\Core\Contracts\Database\Builder;
use Faber\Core\Utils\Collection;
use Faber\Core\Models\Model;
use Faber\Core\Models\RelationLoader;

abstract class Relation
{
    public function __construct(
        public Builder $builder,
        public mixed   $model,
        public string  $related,
        public string  $foreignKey,
        public string  $localKey
    )
    {
    }

    abstract public function getRelation();

    abstract public function getRelations(?Collection $models = null);

    public function withRelation(string $relationName, array &$loads, &$eagerLoadStore, ?Collection $collection)
    {
        $relations = $collection;
        $relationshipData = null;
        foreach ($eagerLoadStore[$relationName][RelationLoader::ORDER_RELATIONS_KEY] as $key => $sort) {
            $relationshipData[$key] = [];
            foreach ($sort as $relationKey) {
                $relationshipData[$key][$relationKey] = [];
                if (!array_key_exists($relationKey, $loads)) {
                    $relationshipData[$key][$relationKey]['collect'] =
                    $relations = $eagerLoadStore[$relationName][$key][$relationKey]->getRelations($relations);
                    $loads[$relationKey] = $relations;
                } else {
                    $relationshipData[$key][$relationKey]['collect'] = $relations = $loads[$relationKey];
                }
                $relationshipData[$key][$relationKey]['eagerLoadData'] =
                    $eagerLoadStore[$relationName][$key][$relationKey];
            }
        }
        return $this->relationshipPackaging($relationshipData);
    }

    protected function relationshipPackaging($relationshipData)
    {
        foreach ($relationshipData as $partial) {
            $partial = array_reverse($partial);
            $partialKeysCount = count(array_keys($partial));
            $iterationCount = 1;
            foreach ($partial as $relationKey => $currentItem) {
                $iterationCount++;
                $next = next($partial);
                if ($next) {
                    foreach ($next['collect'] as $nextCollectionItem) {
                        foreach ($currentItem['collect'] as $collectionItem) {
                            $nextEagerData = $next['eagerLoadData'];
                            $currentEagerData = $currentItem['eagerLoadData'];
                            $nextKey = $nextEagerData->localKey;
                            $itKey = $currentEagerData->foreignKey;

                            if ($partialKeysCount == $iterationCount) {
                                $nextKey = $currentEagerData->localKey;
                            }

                            if ($currentEagerData instanceof BelongsTo) {
                                $nextKey = $nextEagerData->foreignKey;
                                $itKey = $currentEagerData->localKey;
                                if ($partialKeysCount == $iterationCount) {
                                    $nextKey = $currentEagerData->foreignKey;
                                }
                            }

                            if ($nextCollectionItem->{$nextKey} == $collectionItem->{$itKey}) {
                                if (!$nextCollectionItem->relations) $nextCollectionItem->relations = [];
                                if ($currentEagerData instanceof BelongsTo) {
                                    $nextCollectionItem->relations[$relationKey] = $collectionItem;
                                } else {
                                    if (!isset($nextCollectionItem->relations[$relationKey]))
                                        $nextCollectionItem->relations[$relationKey] = [];
                                    $nextCollectionItem->relations[$relationKey][] = $collectionItem;
                                }
                            }
                        }
                    }
                }
            }
        }
        $relationshipData = $relationshipData[array_key_first($relationshipData)];
        return $relationshipData[array_key_first($relationshipData)]['collect'];
    }
}