<?php

namespace InfinityScrollPagination\Service\Abstracts;
use InfinityScrollPagination\Service\Resource\ResourceIterator;
use Iterator;

abstract class Resource
{
    public static function iterator(Iterator $iterator): ResourceIterator
    {
        return new ResourceIterator($iterator);
    }
}