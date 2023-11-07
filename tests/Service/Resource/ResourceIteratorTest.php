<?php

namespace Tests\Service\Resource;

use InfinityScrollPagination\Service\Implementation\Request;
use InfinityScrollPagination\Service\Resource\ResourceIterator;
use PHPUnit\Framework\TestCase;

class ResourceIteratorTest extends TestCase
{
    public function test() {
        $request = new Request([]);
        $request->setIdentifyKey('id');
        $request->setTo(2);
        $request->setPage(2);

        $NOT_GENERATE_ARRAY = [
            (object)["id" => (1)],
            (object)["id" => (2)],
            (object)["id" => (3)],
            (object)["id" => (4)],
        ];

        $this->assertEquals(
            '{"payload":[{"id":3},{"id":4}],"header":{"countItems":2,"identifyKey":"id"},"meta":{"totalItems":4,"hasNext":false,"nextIdentify":"-1"}}',
            (new ResourceIterator((new \ArrayObject($NOT_GENERATE_ARRAY))->getIterator()))->get($request)  . ''
        );
    }
}
