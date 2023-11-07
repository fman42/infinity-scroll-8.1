<?php

namespace Tests\Service\Implementation;

use InfinityScrollPagination\Service\Implementation\Header;
use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    public function test(): void
    {
        $header = new Header();
        $this->assertEquals('{"identifyKey":""}', $header . '');

        $header = new Header();
        $header->setCountItems(10);
        $this->assertEquals('{"countItems":10,"identifyKey":""}', $header . '');

        $header = new Header();
        $header->setCountItems(10);
        $header->setIdentifyKey(10);
        $this->assertEquals('{"countItems":10,"identifyKey":"10"}', $header . '');
    }
}
