<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Abstracts\FilterEnum;
use InfinityScrollPagination\Service\Contracts\Filter as FilterContract;

/**
 * @method static FilterContract SORT(string $value = 'ASC'|'DESC', ?string $identifyKey = null)
 * @method static FilterContract LIKE(string $value, ?string $identifyKey = null)
 * @method static FilterContract FIND(string $value, ?string $identifyKey = null)
 * @method static FilterContract WHEREIN(array $value = [1,2,3], ?string $identifyKey = null)
 * @method static FilterContract WHERENOTIN(array $value = [1,2,3], ?string $identifyKey = null)
 * @method static FilterContract BETWEEN(array $value = [FROM, TO], ?string $identifyKey = null)
 * @method static FilterContract LESS(string $value, ?string $identifyKey = null)
 * @method static FilterContract OVER(string $value, ?string $identifyKey = null)
 * @method static FilterContract EQUAL_OR_LESS(string $value, ?string $identifyKey = null)
 * @method static FilterContract EQUAL_OR_OVER(string $value, ?string $identifyKey = null)
 */
final class Filters
{
    private static ?FilterContract $filter = null;
    private static string $enum = FilterEnum::class;
    final public static function __callStatic($name, $arguments)
    {
        if (!self::$filter) {
            self::$filter = new Filter();
        }

        $enum = self::$enum;
        if (is_array($arguments[0])) {
            $arguments[0] = json_encode($arguments[0]);
        }
        return $enum::{$name}(clone self::$filter, ...$arguments);
    }
}