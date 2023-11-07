<?php

namespace Tests\Service\Implementation;

use InfinityScrollPagination\Service\Implementation\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function test(): void
    {
        $page = new Page();
        $page->setTotalPages(1);
        $page->setCurrentPage(100);
        $this->assertEquals('{"totalPages":1,"currentPage":1}', $page . '');

        $page = new Page();
        $page->setTotalPages(10);
        $page->setCurrentPage(-1);
        $this->assertEquals('{"totalPages":10,"currentPage":1,"nextPage":2}', $page . '');

        $page = new Page();
        $page->setTotalPages(1);
        $page->setCurrentPage(-1);
        $this->assertEquals('{"totalPages":1,"currentPage":1}', $page . '');

        $page = new Page();
        $page->setTotalPages(99);
        $page->setCurrentPage(2);
        $this->assertEquals('{"totalPages":99,"currentPage":2,"nextPage":3,"prevPage":1}', $page . '');

        $page = new Page();
        $page->setTotalPages(-11);
        $page->setCurrentPage(100);
        $this->assertEquals('null', $page . '');

        $page = new Page();
        $page->setTotalPages(0);
        $page->setCurrentPage(100);
        $this->assertEquals('null', $page . '');
    }
}
