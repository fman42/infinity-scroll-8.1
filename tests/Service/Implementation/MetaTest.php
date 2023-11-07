<?php

namespace Tests\Service\Implementation;

use InfinityScrollPagination\Service\Implementation\Meta;
use InfinityScrollPagination\Service\Implementation\Page;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function test(): void
    {
        $this->assertEquals('null', (new Meta()) . '');

        $meta = new Meta();
        $meta->setHasNext(true);
        $this->assertEquals('null', $meta . '');

        $meta = new Meta();
        $meta->setNextIdentify('123');
        $meta->setHasNext(false);
        $this->assertEquals('{"totalItems":0,"hasNext":false,"nextIdentify":"-1"}', $meta . '');

        $meta = new Meta();
        $meta->setNextIdentify('321');
        $meta->setHasNext(false);
        $meta->setTotalItems(10);
        $this->assertEquals('{"totalItems":10,"hasNext":false,"nextIdentify":"-1"}', $meta . '');

        $meta = new Meta();
        $meta->setHasNext(false);
        $meta->setTotalItems(10);
        $this->assertEquals('{"totalItems":10,"hasNext":false,"nextIdentify":"-1"}', $meta . '');

        $meta = new Meta();
        $meta->setNextIdentify('213');
        $meta->setHasNext(false);
        $meta->setTotalItems(10);
        $meta->setPage(new Page());
        $this->assertEquals('{"totalItems":10,"hasNext":false,"nextIdentify":"-1"}', $meta . '');

        $meta = new Meta();
        $meta->setNextIdentify('231');
        $meta->setHasNext(true);
        $meta->setTotalItems(10);
        $meta->setPage($page = new Page());
        $page->setTotalPages(1);
        $this->assertEquals(
            '{"totalItems":10,"hasNext":true,"nextIdentify":"231","page":{"totalPages":1,"currentPage":1}}',
            $meta . ''
        );
    }
}
