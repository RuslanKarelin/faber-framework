<?php

namespace Faber\Core\Database\Traits;

trait BuilderRelation
{
    public function hasOne(string $key, mixed $value): mixed
    {
        return $this->where($key, $value)
            ->limit(1)
            ->get()
            ->first();
    }

    public function belongsTo(string $key, mixed $value): mixed
    {
        return $this->where($key, $value)
            ->limit(1)
            ->get()
            ->first();
    }

    public function hasMany(string $key, mixed $value): mixed
    {
        return $this->where($key, $value)
            ->get();
    }

    public function withRelation(string $key, mixed $value): mixed
    {
        $this->whereIn = [];
        $this->prefixWhere = ' where ';
        return $this->whereIn($key, $value)
            ->get();
    }
}