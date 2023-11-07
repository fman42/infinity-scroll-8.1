<?php

namespace InfinityScrollPagination\Service\Abstracts;

use InfinityScrollPagination\Service\Contracts\Filter as FilterContract;

abstract class FilterEnum
{
    private static function initFilter(FilterContract $filter, string $name, string $value, ?string $identifyKey = null): FilterContract
    {
        $filter->setName($name);
        $filter->setValue($value);
        if ($identifyKey) {
            $filter->setIdentifyKey($identifyKey);
        }
        return $filter;
    }

    final public static function SORT(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function LIKE(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function FIND(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function WHEREIN(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function WHERENOTIN(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function BETWEEN(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function LESS(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function OVER(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function EQUAL_OR_LESS(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }

    final public static function EQUAL_OR_OVER(FilterContract $instance, string $value, ?string $identifyKey = null): FilterContract
    {
        return self::initFilter($instance, __FUNCTION__, $value, $identifyKey);
    }
}