<?php

namespace InfinityScrollPagination\Service\Contracts;

use InfinityScrollPagination\Service\Contracts\Integration\FilterInclude;

interface FilterFactory extends FilterInclude
{
    /**
     * @param string $value ASC / DESC
     * @param string|null $identifyKey
     * @return void
     */
    public function SORT(string $value = "ASC", ?string $identifyKey = null): void;

    /**
     * @param string $value %YOU_EXPRESSION% => YOU_EXPRESSION
     * @param string|null $identifyKey
     * @return void
     */
    public function LIKE(string $value = "", ?string $identifyKey = null): void;

    /**
     * @param string $value FIND ENTITY FROM
     * @param string|null $identifyKey
     * @return void
     */
    public function FIND(string $value = "", ?string $identifyKey = null): void;

    /**
     * @param string $value "[1,2,3 ...]"
     * @param string|null $identifyKey
     * @return void
     */
    public function WHEREIN(string $value = "[]", ?string $identifyKey = null): void;

    /**
     * @param string $value "[1,2,3 ...]"
     * @param string|null $identifyKey
     * @return void
     */
    public function WHERENOTIN(string $value = "[]", ?string $identifyKey = null): void;

    /**
     * @param string $value [from, to] : COUNT = 2
     * @param string|null $identifyKey
     * @return void
     */
    public function BETWEEN(string $value = "[null, null]", ?string $identifyKey = null): void;

    /**
     * @param string $value a < b : a LESS b
     * @param string|null $identifyKey
     * @return void
     */
    public function LESS(string $value, ?string $identifyKey = null): void;

    /**
     * @param string $value a > b : a OVER b
     * @param string|null $identifyKey
     * @return void
     */
    public function OVER(string $value, ?string $identifyKey = null): void;

    /**
     * @param string $value a <= b : a EQUAL_OR_LESS b
     * @param string|null $identifyKey
     * @return void
     */
    public function EQUAL_OR_LESS(string $value, ?string $identifyKey = null): void;

    /**
     * @param string $value a >= b : a EQUAL_OR_OVER b
     * @param string|null $identifyKey
     * @return void
     */
    public function EQUAL_OR_OVER(string $value, ?string $identifyKey = null): void;

    public function __construct();

    public function get();

    public function getDefaultIdentifyKey(?string $identifyKey = null): string;
}