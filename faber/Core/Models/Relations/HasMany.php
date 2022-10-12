<?php

namespace Faber\Core\Models\Relations;

use Faber\Core\Utils\Collection;

class HasMany extends Relation
{
    public function getRelation()
    {
        return $this->builder->hasMany($this->foreignKey, $this->model->{$this->localKey});
    }

    public function getRelations(?Collection $models = null)
    {
        if ($models) {
            $ids = $models->pluck($this->localKey)->toArray();
        } else {
            $ids = [$this->model->{$this->localKey}];
        }
        return $this->builder->withRelation($this->foreignKey, $ids);
    }
}