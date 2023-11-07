<?php

namespace InfinityScrollPagination\Laravel\Abstracts;

use Illuminate\Support\Enumerable;
use InfinityScrollPagination\Laravel\Implementation\Resource\ResourceEnumerable;

abstract class Resource extends \InfinityScrollPagination\Service\Abstracts\Resource
{
    public static function enumerable(Enumerable $enumerable): ResourceEnumerable
    {
        return new ResourceEnumerable($enumerable);
    }
}