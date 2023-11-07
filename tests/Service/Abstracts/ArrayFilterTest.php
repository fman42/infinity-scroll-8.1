<?php

namespace Tests\Service\Abstracts;

use InfinityScrollPagination\Service\Abstracts\ArrayFilter;
use InfinityScrollPagination\Service\Contracts\FilterFactory;
use PHPUnit\Framework\TestCase;

class ArrayFilterTest extends TestCase
{
    private function arrayFill(string $key, array $keys, bool $isObject = false): array
    {
        return array_map(function ($item) use ($key, $isObject) {
            return $isObject ? (object)[$key => $item] : [$key => $item];
        }, $keys);
    }

    private function getFilter() : ArrayFilter
    {
        return new class extends ArrayFilter {
            public function getDefaultIdentifyKey(?string $identifyKey = null): string
            {
                return $identifyKey ?: "key";
            }
        };
    }

    public function testSortASC() {

        $mock = $this->arrayFill("key", [2, 3, 0, 1]);
        $filter = $this->getFilter();
        $filter->setArray($mock);
        $filter->SORT("ASC", "key");

        $this->assertEquals($this->arrayFill("key", [0, 1, 2, 3]), $filter->get());

        $mock = $this->arrayFill("key", [2, 3, 0, 1], true);
        $filter = $this->getFilter();
        $filter->setArray($mock);
        $filter->SORT("ASC", "key");

        $this->assertEquals($this->arrayFill("key", [0, 1, 2, 3], true), $filter->get());
    }

    public function testSortDesc() {

        $mock = $this->arrayFill("key", [2, 3, 0, 1]);
        $filter = $this->getFilter();
        $filter->setArray($mock);
        $filter->SORT("DESC", "key");

        $this->assertEquals($this->arrayFill("key", [3, 2, 1, 0]), $filter->get());

        $mock = $this->arrayFill("key", [2, 3, 0, 1], true);
        $filter = $this->getFilter();
        $filter->setArray($mock);
        $filter->SORT("DESC", "key");

        $this->assertEquals($this->arrayFill("key", [3, 2, 1, 0], true), $filter->get());
    }

    public function testLike() {
        $mock = $this->arrayFill("name", ["abcdefg", "bfgcdea", "cgabdfe"]);
        $filter = $this->getFilter();
        $filter->setArray($mock);
        $filter->LIKE("gcd", "name");

        $this->assertEquals([["name" => "bfgcdea"]], $filter->get());

        $mock = $this->arrayFill("name", ["abcdefg", "bfgcdea", "cgabdfe"], true);
        $filter = $this->getFilter();
        $filter->setArray($mock);
        $filter->LIKE("gcd", "name");

        $this->assertEquals([(object)["name" => "bfgcdea"]], $filter->get());
    }

    public function test() {
        $cases = [
            function(FilterFactory $filter) { $filter->FIND("1", "key"); return [["key" => 1]]; },
            function(FilterFactory $filter) { $filter->FIND("2", "key"); return [["key" => 2]]; },
            function(FilterFactory $filter) {$filter->WHEREIN("[1, 0]", "key"); return [["key" => 0], ["key" => 1]]; },
            function(FilterFactory $filter) { $filter->WHEREIN("[2, 0]", "key"); return [["key" => 2], ["key" => 0]]; },
            function(FilterFactory $filter) {$filter->WHERENOTIN("[1, 0]", "key"); return [["key" => 2], ["key" => 3]]; },
            function(FilterFactory $filter) { $filter->WHERENOTIN("[2, 0]", "key"); return [["key" => 3], ["key" => 1]]; },
            function(FilterFactory $filter) { $filter->BETWEEN("[1, 2]", "key"); return [["key" => 2], ["key" => 1]]; },
            function(FilterFactory $filter) { $filter->BETWEEN("[2, 3]", "key"); return [["key" => 2], ["key" => 3]]; },
            function(FilterFactory $filter) { $filter->LESS("2", "key"); return [["key" => 0], ["key" => 1]]; },
            function(FilterFactory $filter) { $filter->LESS("1", "key"); return [["key" => 0]]; },
            function(FilterFactory $filter) { $filter->OVER("2", "key"); return [["key" => 3]]; },
            function(FilterFactory $filter) { $filter->OVER("1", "key"); return [["key" => 2], ["key" => 3]]; },
            function(FilterFactory $filter) { $filter->EQUAL_OR_LESS("2", "key"); return [["key" => 2], ["key" => 0], ["key" => 1]]; },
            function(FilterFactory $filter) { $filter->EQUAL_OR_LESS("1", "key"); return [["key" => 0], ["key" => 1]]; },
            function(FilterFactory $filter) { $filter->EQUAL_OR_OVER("2", "key"); return [["key" => 2], ["key" => 3]]; },
            function(FilterFactory $filter) { $filter->EQUAL_OR_OVER("1", "key"); return [["key" => 2], ["key" => 3], ["key" => 1]]; },
        ];

        foreach ($cases as $test) {
            $mock = $this->arrayFill("key", [2, 3, 0, 1]);
            $filter = $this->getFilter();
            $filter->setArray($mock);
            $this->assertEquals($test($filter), $filter->get());

            $mock = $this->arrayFill("key", [2, 3, 0, 1], true);
            $filter = $this->getFilter();
            $filter->setArray($mock);
            $this->assertEquals(array_map(function ($item) { return (object)$item; }, $test($filter)), $filter->get());
        }
    }
}
