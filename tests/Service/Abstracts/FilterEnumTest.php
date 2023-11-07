<?php

namespace Tests\Service\Abstracts;

use InfinityScrollPagination\Service\Abstracts\FilterEnum;
use InfinityScrollPagination\Service\Implementation\Filter;
use PHPUnit\Framework\TestCase;

class FilterEnumTest extends TestCase
{
    public function testSort(): void
    {
        $this->assertEquals(
            '{"name":"SORT","identifyKey":"2","value":"1"}',
            FilterEnum::SORT(new Filter(), '1', '2') . ''
        );
    }

    public function testWhereIn(): void
    {
        $this->assertEquals(
            '{"name":"WHEREIN","identifyKey":"2","value":"[1,2,3]"}',
            FilterEnum::WHEREIN(new Filter(), '[1,2,3]', '2') . ''
        );
    }

    public function testWhereNotIn(): void
    {
        $this->assertEquals(
            '{"name":"WHERENOTIN","identifyKey":"2","value":"[1,2,3]"}',
            FilterEnum::WHERENOTIN(new Filter(), '[1,2,3]', '2') . ''
        );
    }
}
