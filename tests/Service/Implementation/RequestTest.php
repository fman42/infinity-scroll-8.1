<?php

namespace Tests\Service\Implementation;

use InfinityScrollPagination\Service\Implementation\Filter;
use InfinityScrollPagination\Service\Implementation\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function test(): void
    {
        $request = new Request([]);
        $request->setTo('1');

        $this->assertEquals('{"identifyKey":"id","to":1}', $request . '');

        $request = new Request([]);
        $request->setIdentifyKey('1');

        $this->assertEquals('{"identifyKey":"1","to":25}', $request . '');

        $request = new Request([]);
        $request->setNextIdentify('1');

        $this->assertEquals(
            '{"identifyKey":"id","to":25,"nextIdentify":"1"}',
            $request . ''
        );

        $request = new Request([]);
        $request->setPage('1');

        $this->assertEquals(
            '{"identifyKey":"id","to":25,"page":1}',
            $request . ''
        );

        $request = new Request([]);
        $request->setSelect(['1', 2]);

        $this->assertEquals(
            '{"identifyKey":"id","to":25,"select":["1"]}',
            $request . ''
        );

        $request = new Request([]);
        $f1 = new Filter();
        $f1->setValue('1');
        $f1->setName('1');
        $f1->setIdentifyKey('1');
        $f2 = new Filter();
        $f2->setValue('1');
        $f2->setName('1');
        $request->setFilter([$f1, $f2, ["name" => "2", "identifyKey" => 2, "value" => 3]]);

        $this->assertEquals(
            '{"identifyKey":"id","to":25,"filter":[{"name":"1","identifyKey":"1","value":"1"},{"name":"2","identifyKey":"2","value":"3"}]}',
            $request . ''
        );
    }
}
