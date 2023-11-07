<?php

namespace Tests\Service\Implementation;

use InfinityScrollPagination\Service\Implementation\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function test(): void
    {
        $filter = new Filter();
        $this->assertEquals('null', $filter . '');

        $filter = new Filter();
        $filter->setName("asdfasdf");
        $this->assertTrue($filter->isEmpty());

        $filter = new Filter();
        $filter->setValue("asdfasdf");
        $this->assertTrue($filter->isEmpty());

        $filter = new Filter();
        $filter->setIdentifyKey("asdfasdf");
        $this->assertTrue($filter->isEmpty());

        $filter = new Filter();
        $filter->setName("asdfasdf");
        $filter->setValue("asdfasdf");
        $filter->setIdentifyKey("asdfasdf");
        $this->assertFalse($filter->isEmpty());
    }
}
