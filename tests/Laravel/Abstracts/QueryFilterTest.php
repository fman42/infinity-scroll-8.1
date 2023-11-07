<?php

namespace Tests\Laravel\Abstracts;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use InfinityScrollPagination\Laravel\Abstracts\QueryFilter;
use PHPUnit\Framework\TestCase;

class QueryFilterTest extends TestCase
{
    public function getMockData(): QueryFilter
    {
        $filter = (new class extends QueryFilter {
            public function getDefaultIdentifyKey(?string $identifyKey = null): string
            {
                return $identifyKey ?: "ID";
            }
        });
        $filter->setBuilder(new Builder(new Connection(function () {})));
        return $filter;
    }

    public function testSort(): void
    {
        $filter = $this->getMockData();
        $filter->SORT("ASC", "SORT");
        $filter->SORT("DESC", "SORT");

        $this->assertEquals(
            'select * order by "SORT" asc, "SORT" desc',
            $filter->get()->toSql()
        );
    }

    public function testLike(): void
    {
        $filter = $this->getMockData();
        $filter->LIKE("LIKE", "LIKE");
        $filter->LIKE("LI", "LIKE");

        $this->assertEquals(
            'select * where "LIKE" LIKE ?',
            $filter->get()->toSql()
        );
    }

    public function testFind(): void
    {
        $filter = $this->getMockData();
        $filter->FIND("3", "FIND");

        $this->assertEquals(
            'select * where "FIND" = ?',
            $filter->get()->toSql()
        );
    }

    public function testWhereIn()
    {
        $filter = $this->getMockData();
        $filter->WHEREIN("[1,2,3]", "WHEREIN");

        $this->assertEquals(
            'select * where "WHEREIN" in (?, ?, ?)',
            $filter->get()->toSql()
        );
    }

    public function testWhereNotIn()
    {
        $filter = $this->getMockData();
        $filter->WHERENOTIN("[1,2,3]", "WHERENOTIN");

        $this->assertEquals(
            'select * where "WHERENOTIN" not in (?, ?, ?)',
            $filter->get()->toSql()
        );
    }

    public function testBetween()
    {
        $filter = $this->getMockData();
        $filter->BETWEEN("[1]", "ERR_BETWEEN");
        $filter->BETWEEN("[2,3]", "BETWEEN");

        $this->assertEquals(
            'select * where "BETWEEN" between ? and ?',
            $filter->get()->toSql()
        );
    }

    public function testLessAndEtc() {
        $filter = $this->getMockData();
        $filter->LESS("10", "LESS");
        $filter->EQUAL_OR_LESS("10", "EQUAL_OR_LESS");

        $this->assertEquals(
            'select * where "LESS" > ? and "EQUAL_OR_LESS" >= ?',
            $filter->get()->toSql()
        );
    }

    public function testOverAndEtc() {
        $filter = $this->getMockData();
        $filter->OVER("11", "OVER");
        $filter->EQUAL_OR_OVER("11", "EQUAL_OR_OVER");

        $this->assertEquals(
            'select * where "OVER" < ? and "EQUAL_OR_OVER" <= ?',
            $filter->get()->toSql()
        );
    }
}
