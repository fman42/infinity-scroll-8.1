<?php

namespace InfinityScrollPagination\Service\Contracts\Integration;

use InfinityScrollPagination\Service\Contracts\Filter;

interface FilterInclude
{
    public function includeFilter(Filter $filter): void;
}