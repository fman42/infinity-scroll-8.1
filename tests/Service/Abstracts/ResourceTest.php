<?php

namespace Tests\Service\Abstracts;

use InfinityScrollPagination\Service\Abstracts\Resource;
use InfinityScrollPagination\Service\Implementation\Request;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function test(): void
    {
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

        $res = Resource::iterator(
            (new \ArrayObject($NOT_GENERATE_ARRAY))->getIterator()
        )->get($request);

        $this->assertEquals(
            '{"payload":[{"id":3},{"id":4}],"header":{"countItems":2,"identifyKey":"id"},"meta":{"totalItems":4,"hasNext":false,"nextIdentify":"-1"}}',
            $res . ''
        );

        $request->setPage(1);

        $res = Resource::iterator(
            (new \ArrayObject($NOT_GENERATE_ARRAY))->getIterator()
        )->get($request);

        $this->assertEquals(
            '{"payload":[{"id":1},{"id":2}],"header":{"countItems":2,"identifyKey":"id"},"meta":{"totalItems":4,"hasNext":true,"nextIdentify":"I:2"}}',
            $res . ''
        );
    }
}
