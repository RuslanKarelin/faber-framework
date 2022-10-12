<?php

namespace Faber\Core\Models\Relations;

use Faber\Core\Utils\Collection;

class BelongsTo extends Relation
{
    function getRelation()
    {
        return $this->builder->belongsTo($this->localKey, $this->model->{$this->foreignKey});
    }

    public function getRelations(?Collection $models = null)
    {
        if ($models) {
            $ids = $models->pluck($this->foreignKey)->toArray();
        } else {
            $ids = [$this->model->{$this->foreignKey}];
        }
        return $this->builder->withRelation($this->localKey, $ids);
    }
}