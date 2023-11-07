<?php

namespace InfinityScrollPagination\Laravel\Abstracts;

use Illuminate\Database\Query\Builder;
use InfinityScrollPagination\Service\Abstracts\AbstractFilter;
use InfinityScrollPagination\Service\Contracts\FilterFactory;

abstract class QueryFilter extends AbstractFilter implements FilterFactory
{
    protected Builder $builder;
    final public function SORT(string $value = "ASC", ?string $identifyKey = null): void
    {
        if ($value == "ASC" || $value == "DESC" || $value == "asc" || $value == "desc") {
            $this->builder->orderBy($this->getDefaultIdentifyKey($identifyKey), strtolower($value));
        }
    }

    final public function LIKE(string $value = "", ?string $identifyKey = null): void
    {
        if (mb_strlen($value) >= 3) {
            $this->builder->where($this->getDefaultIdentifyKey($identifyKey), "LIKE", "%$value%");
        }
    }

    final public function FIND(string $value = "", ?string $identifyKey = null): void
    {
        if ($value) {
            $this->builder->where($this->getDefaultIdentifyKey($identifyKey), $value);
        }
    }

    final public function WHEREIN(string $value = "[]", ?string $identifyKey = null): void
    {
        $value = json_decode($value, true);
        if ($value && is_array($value) && count($value)) {
            $this->builder->whereIn($this->getDefaultIdentifyKey($identifyKey), $value);
        }
    }

    final public function WHERENOTIN(string $value = "[]", ?string $identifyKey = null): void
    {
        $value = json_decode($value, true);
        if ($value && is_array($value) && count($value)) {
            $this->builder->whereNotIn($this->getDefaultIdentifyKey($identifyKey), $value);
        }
    }

    final public function BETWEEN(string $value = "[null, null]", ?string $identifyKey = null): void
    {
        $value = json_decode($value, true);
        if ($value && is_array($value) && count($value) == 2) {
            $this->builder->whereBetween($this->getDefaultIdentifyKey($identifyKey), $value);
        }
    }

    final public function LESS(string $value, ?string $identifyKey = null): void
    {
        $this->builder->where($this->getDefaultIdentifyKey($identifyKey), ">", $value);
    }

    final public function OVER(string $value, ?string $identifyKey = null): void
    {
        $this->builder->where($this->getDefaultIdentifyKey($identifyKey), "<", $value);
    }

    final public function EQUAL_OR_LESS(string $value, ?string $identifyKey = null): void
    {
        $this->builder->where($this->getDefaultIdentifyKey($identifyKey), ">=", $value);
    }

    final public function EQUAL_OR_OVER(string $value, ?string $identifyKey = null): void
    {
        $this->builder->where($this->getDefaultIdentifyKey($identifyKey), "<=", $value);
    }

    public function setBuilder(Builder $builder): void
    {
        $this->builder = $builder;
    }

    final public function __construct()
    {
    }

    final public function get(): Builder
    {
        return $this->builder;
    }
}