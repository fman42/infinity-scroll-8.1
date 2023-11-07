<?php

namespace InfinityScrollPagination\Service\Abstracts;

use InfinityScrollPagination\Service\Contracts\FilterFactory;

abstract class ArrayFilter extends AbstractFilter implements FilterFactory
{
    protected array $array;

    final public function SORT(string $value = "ASC", ?string $identifyKey = null): void
    {
        if ($value == "ASC" || $value == "DESC" || $value == "asc" || $value == "desc") {
            $key = $this->getDefaultIdentifyKey($identifyKey);
            $isAsc = $value == "asc" || $value == "ASC";

            usort($this->array, function ($before, $after) use ($key, $isAsc) {
                return strcmp(
                    $isAsc ? ($before->{$key} ?? $before[$key]) : ($after->{$key} ?? $after[$key]),
                    $isAsc ? ($after->{$key} ?? $after[$key]) : ($before->{$key} ?? $before[$key])
                );
            });
        }
    }

    final public function LIKE(string $value = "", ?string $identifyKey = null): void
    {
        if (mb_strlen($value) >= 3) {
            $key = $this->getDefaultIdentifyKey($identifyKey);

            $array = array_filter($this->array, function ($item) use ($key, $value) {
                $itemValue = $item->{$key} ?? $item[$key];
                $itemValueLength = strlen($itemValue);
                $valueLength = strlen($value);

                if ($itemValueLength > $valueLength) {
                    return strpos($itemValue, $value) !== false;
                }
                if ($itemValueLength == $valueLength) {
                    return $itemValue == $value;
                }
                return false;
            });

            $this->array = array_values($array);
        }
    }

    final public function FIND(string $value = "", ?string $identifyKey = null): void
    {
        $key = $this->getDefaultIdentifyKey($identifyKey);

        $array = array_filter($this->array, function ($item) use ($key, $value) {
            $itemValue = $item->{$key} ?? $item[$key];
            return $itemValue == $value;
        });

        $this->array = array_values($array);
    }

    final public function WHEREIN(string $value = "[]", ?string $identifyKey = null): void
    {
        $value = json_decode($value, true);
        if ($value && is_array($value) && count($value)) {
            $key = $this->getDefaultIdentifyKey($identifyKey);

            $array = array_filter($this->array, function ($item) use ($key, $value) {
                $itemValue = $item->{$key} ?? $item[$key];
                return in_array($itemValue, $value);
            });

            $this->array = array_values($array);
        }
    }

    final public function WHERENOTIN(string $value = "[]", ?string $identifyKey = null): void
    {
        $value = json_decode($value, true);
        if ($value && is_array($value) && count($value)) {
            $key = $this->getDefaultIdentifyKey($identifyKey);

            $array = array_filter($this->array, function ($item) use ($key, $value) {
                $itemValue = $item->{$key} ?? $item[$key];
                return !in_array($itemValue, $value);
            });

            $this->array = array_values($array);
        }
    }

    final public function BETWEEN(string $value = "[null, null]", ?string $identifyKey = null): void
    {
        $value = json_decode($value, true);
        if ($value && is_array($value) && count($value) == 2) {
            $from = $value[0];
            $to = $value[1];

            if ($from > $to) {
                $this->array = [];
                return;
            }

            $key = $this->getDefaultIdentifyKey($identifyKey);

            $array = array_filter($this->array, function ($item) use ($key, $from, $to) {
                $itemValue = $item->{$key} ?? $item[$key];
                return $from <= $itemValue && $itemValue <= $to;
            });

            $this->array = array_values($array);
        }
    }

    final public function LESS(string $value, ?string $identifyKey = null): void
    {
        $key = $this->getDefaultIdentifyKey($identifyKey);

        $array = array_filter($this->array, function ($item) use ($key, $value) {
            $itemValue = $item->{$key} ?? $item[$key];
            return $itemValue < $value;
        });

        $this->array = array_values($array);
    }

    final public function OVER(string $value, ?string $identifyKey = null): void
    {
        $key = $this->getDefaultIdentifyKey($identifyKey);

        $array = array_filter($this->array, function ($item) use ($key, $value) {
            $itemValue = $item->{$key} ?? $item[$key];
            return $itemValue > $value;
        });

        $this->array = array_values($array);
    }

    final public function EQUAL_OR_LESS(string $value, ?string $identifyKey = null): void
    {
        $key = $this->getDefaultIdentifyKey($identifyKey);

        $array = array_filter($this->array, function ($item) use ($key, $value) {
            $itemValue = $item->{$key} ?? $item[$key];
            return $itemValue <= $value;
        });

        $this->array = array_values($array);
    }

    final public function EQUAL_OR_OVER(string $value, ?string $identifyKey = null): void
    {
        $key = $this->getDefaultIdentifyKey($identifyKey);

        $array = array_filter($this->array, function ($item) use ($key, $value) {
            $itemValue = $item->{$key} ?? $item[$key];
            return $itemValue >= $value;
        });

        $this->array = array_values($array);
    }

    public function setArray(array $array): void
    {
        $this->array = $array;
    }

    final public function __construct()
    {
    }

    final public function get(): array
    {
        return $this->array;
    }
}