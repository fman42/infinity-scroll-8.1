<?php

namespace InfinityScrollPagination\Service\Abstracts;

use InfinityScrollPagination\Service\Contracts\Filter as FilterContract;
use InfinityScrollPagination\Service\Contracts\Integration\FilterInclude;

abstract class AbstractFilter implements FilterInclude
{
    final public function includeFilter(FilterContract $filter): void
    {
        $name = $filter->getName();
        if (method_exists($this, $name)) {
            try {
                $this->{$name}($filter->getValue(), $filter->getIdentifyKey());
            } catch (\Exception $ignore) {}
        }
    }
}