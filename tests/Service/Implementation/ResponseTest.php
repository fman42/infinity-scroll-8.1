<?php

namespace Tests\Service\Implementation;

use InfinityScrollPagination\Service\Implementation\Meta;
use InfinityScrollPagination\Service\Implementation\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function test(): void
    {
        $response = new Response();
        $this->assertEquals(
            '{"payload":[],"header":{"identifyKey":""},"meta":null}',
            $response . ''
        );

        $response = new Response();
        $response->setPayload(["1", "2", "3"]);

        $this->assertEquals(
            '{"payload":["1","2","3"],"header":{"countItems":3,"identifyKey":""},"meta":null}',
            $response . ''
        );

        $response = new Response();
        $response->getHeader()->setIdentifyKey("*");
        $response->setPayload(["3", "2", "1"]);

        $this->assertEquals(
            '{"payload":["3","2","1"],"header":{"countItems":3,"identifyKey":"*"},"meta":null}',
            $response . ''
        );

        $response = new Response();
        $response->setMeta(new Meta());
        $response->getHeader()->setIdentifyKey("*");
        $response->setPayload(["2", "1", "3"]);

        $this->assertEquals(
            '{"payload":["2","1","3"],"header":{"countItems":3,"identifyKey":"*"},"meta":null}',
            $response . ''
        );
    }
}
