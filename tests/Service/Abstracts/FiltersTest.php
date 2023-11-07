<?php

namespace Tests\Service\Abstracts;

use InfinityScrollPagination\Service\Abstracts\ArrayFilter;
use InfinityScrollPagination\Service\Abstracts\FilterEnum;
use InfinityScrollPagination\Service\Implementation\Filters;
use PHPUnit\Framework\TestCase;

/**
 * @see FilterEnum
 */
class FiltersTest extends TestCase
{
    public function testSort(): void
    {
        $this->assertEquals(
            '{"name":"SORT","identifyKey":"2","value":"1"}',
            Filters::SORT('1', '2') . ''
        );
    }

    public function testWhereIn(): void
    {
        $this->assertEquals(
            '{"name":"WHEREIN","identifyKey":"2","value":"[1,2,3]"}',
            Filters::WHEREIN([1, 2, 3], '2') . ''
        );
    }

    public function testWhereNotIn(): void
    {
        $this->assertEquals(
            '{"name":"WHERENOTIN","identifyKey":"2","value":"[1,2,3]"}',
            Filters::WHERENOTIN([1, 2, 3], '2') . ''
        );
    }

    public function testSortArrayAsc(): void
    {
        $filter = new class extends ArrayFilter {
            public function getDefaultIdentifyKey(?string $identifyKey = null): string
            {
                return $identifyKey ?: 'id';
            }
        };

        $filter->setArray([['id' => 4], ['id' => 0], ['id' => 8], ['id' => 1]]);

        $filter->includeFilter(Filters::SORT('ASC', 'id'));

        $this->assertEquals([['id' => 0], ['id' => 1], ['id' => 4], ['id' => 8]], $filter->get());
    }

    public function testSortArrayDesc(): void
    {
        $filter = new class extends ArrayFilter {
            public function getDefaultIdentifyKey(?string $identifyKey = null): string
            {
                return $identifyKey ?: 'id';
            }
        };

        $filter->setArray([['id' => 4], ['id' => 0], ['id' => 8], ['id' => 1],]);

        $filter->includeFilter(Filters::SORT('DESC', 'id'));

        $this->assertEquals([['id' => 8], ['id' => 4], ['id' => 1], ['id' => 0],], $filter->get());
    }
}
