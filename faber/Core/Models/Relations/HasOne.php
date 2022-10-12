<?php

namespace Faber\Core\Models\Relations;

use Faber\Core\Utils\Collection;

class HasOne extends Relation
{
    function getRelation()
    {
        return $this->builder->hasOne($this->foreignKey, $this->model->{$this->localKey});
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