<?php

namespace Tests\Laravel\Integration\TestCase_1;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Enumerable;
use InfinityScrollPagination\Laravel\Abstracts\Resource;
use InfinityScrollPagination\Service\Implementation\Filters;
use InfinityScrollPagination\Service\Implementation\Meta;
use InfinityScrollPagination\Service\Implementation\Page;
use InfinityScrollPagination\Service\Implementation\Request;
use InfinityScrollPagination\Service\Implementation\Response as ResponseImpl;
use PHPUnit\Framework\TestCase;
use Tests\Laravel\Classes\Controller;
use Tests\Laravel\Classes\Filter;
use Tests\Laravel\Classes\FilterRequest;
use Tests\Laravel\Classes\Ioc;
use Tests\Laravel\Classes\User;

class ControllerTest extends TestCase
{
    private function CLIENT_TO_SERVER(Request $request): FilterRequest
    {
        $raw = json_decode(json_encode($request->toArray()), true);

        $filterRequest = new FilterRequest();
        $filterRequest->merge($raw);

        $this->assertEquals('', Ioc::validateRequest($filterRequest));

        return $filterRequest;
    }

    private function SERVER_TO_CLIENT(Response $response): ResponseImpl
    {
        $data = json_decode($response->getContent(), true);
        $res = new ResponseImpl();
        $res->setPayload($data['payload']);

        if (isset($data['header']['countItems'])) {
            $res->getHeader()->setCountItems($data['header']['countItems']);
        }

        $res->getHeader()->setIdentifyKey($data['header']['identifyKey']);

        $res->setMeta($meta = new Meta());
        $meta->setTotalItems($data['meta']['totalItems']);
        $meta->setHasNext($data['meta']['hasNext']);
        $meta->setNextIdentify($data['meta']['nextIdentify']);

        if (isset($data['meta']['page'])) {
            $meta->setPage($page = new Page());

            $page->setTotalPages($data['meta']['page']['totalPages']);
            $page->setCurrentPage($data['meta']['page']['currentPage']);
            if (isset($data['meta']['page']['nextPage'])) {
                $page->setNextPage($data['meta']['page']['nextPage']);
            }
            if (isset($data['meta']['page']['prevPage'])) {
                $page->setPrevPage($data['meta']['page']['prevPage']);
            }
        }

        return $res;
    }

    public function testSimple()
    {
        $request = new Request([]);
        $request->setIdentifyKey('id');
        $request->addFilter(Filters::SORT('DESC', 'id'));
        $request->addSelect('id');
        $request->addSelect('name');
        $request->setTo(1);
        $request->setPage(1);

        $response = $this->SERVER_TO_CLIENT((new Controller())($this->CLIENT_TO_SERVER($request)));

        $this->assertEquals(['id', 'name'], array_keys($response->getPayload()[0]));
        $this->assertEquals(1, $response->getHeader()->getCountItems());
        $this->assertEquals(true, $response->getMeta()->getHasNext());
        $this->assertEquals(User::COUNT, $response->getMeta()->getTotalItems());
        $this->assertNotEquals('-1', $response->getMeta()->getNextIdentify());
        $this->assertEquals(User::COUNT, $response->getMeta()->getPage()->getTotalPages());
        $this->assertEquals(1, $response->getMeta()->getPage()->getCurrentPage());
        $this->assertEquals(2, $response->getMeta()->getPage()->getNextPage());
        $this->assertEquals(0, $response->getMeta()->getPage()->getPrevPage());

        $request->setTo(2);
        $response = $this->SERVER_TO_CLIENT((new Controller())($this->CLIENT_TO_SERVER($request)));

        $this->assertEquals(User::COUNT / 2, $response->getMeta()->getPage()->getTotalPages());
        $this->assertEquals(User::COUNT, $response->getMeta()->getTotalItems());

        $request->setPage(50);
        $response = $this->SERVER_TO_CLIENT((new Controller())($this->CLIENT_TO_SERVER($request)));

        $this->assertEquals('-1', $response->getMeta()->getNextIdentify());
        $this->assertEquals(false, $response->getMeta()->getHasNext());
        $this->assertEquals(User::COUNT / 2, $response->getMeta()->getPage()->getNextPage());
        $this->assertEquals(49, $response->getMeta()->getPage()->getPrevPage());

        $request->setPage(200);
        $response = $this->SERVER_TO_CLIENT((new Controller())($this->CLIENT_TO_SERVER($request)));

        $this->assertEquals('-1', $response->getMeta()->getNextIdentify());
        $this->assertEquals(false, $response->getMeta()->getHasNext());
        $this->assertEquals(User::COUNT / 2, $response->getMeta()->getPage()->getNextPage());
        $this->assertEquals(49, $response->getMeta()->getPage()->getPrevPage());

        $request->setPage(0);
        $request->setTo(-1);
        $response = $this->SERVER_TO_CLIENT((new Controller())($this->CLIENT_TO_SERVER($request)));

        $this->assertEquals('E:0', $response->getMeta()->getNextIdentify());
        $this->assertEquals(true, $response->getMeta()->getHasNext());
        $this->assertEquals(null, $response->getMeta()->getPage());
        $this->assertEquals(User::COUNT, $response->getMeta()->getTotalItems());
    }

    public function testArray()
    {
        $request = new Request([]);
        $request->setIdentifyKey('id');
        $request->setTo(2);

        $NOT_GENERATE_ARRAY = [
            (object)["id" => 1],
            (object)["id" => 2],
            (object)["id" => 3],
            (object)["id" => 4],
        ];

        $res = Resource::iterator(
            (new \ArrayObject($NOT_GENERATE_ARRAY))->getIterator()
        )->get($request);

        $this->assertEquals(
            '{"payload":[{"id":1},{"id":2}],"header":{"countItems":2,"identifyKey":"id"},"meta":{"totalItems":4,"hasNext":true,"nextIdentify":"I:2"}}',
            $res . ''
        );

        $NOT_GENERATE_COLLECT = new Collection($NOT_GENERATE_ARRAY);

        $res = Resource::iterator(
            $NOT_GENERATE_COLLECT->getIterator()
        )->get($request);

        $this->assertEquals(
            '{"payload":[{"id":1},{"id":2}],"header":{"countItems":2,"identifyKey":"id"},"meta":{"totalItems":4,"hasNext":true,"nextIdentify":"I:2"}}',
            $res . ''
        );

        $GENERATE_ARRAY = function () {
            yield (object)['id' => 1];
        };

        $res = Resource::iterator(
            $GENERATE_ARRAY()
        )->get($request);

        $this->assertEquals(
            '{"payload":[{"id":1}],"header":{"countItems":1,"identifyKey":"id"},"meta":null}',
            $res . ''
        );
    }

    public function testEnumerable() {
        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data[] = (object)["id" => $i];
        }

        $enumerable = (new Collection())->mergeRecursive($data);

        $json = new \stdClass();
        $json->meta = new \stdClass();
        $json->meta->nextIdentify = '';

        $to = 2;
        $check = 'E:' . $to;
        $counter = 0;

        do {
            $request = new Request([]);
            $request->setIdentifyKey('id');
            $request->setTo($to);
            $request->setNextIdentify($json->meta->nextIdentify);
            $filterRequest = new FilterRequest();
            $filterRequest->merge($request->toArray());

            $res = Resource::enumerable(
                $enumerable
            )->get($filterRequest);

            $counter += $to;
            $check = 'E:' . $counter;
            if ($counter == 100) {
                $check = '-1';
            }

            $json = json_decode($res->getContent());
            $nextIdentify = $json->meta->nextIdentify;
            $this->assertEquals($check, $nextIdentify);
            $this->assertEquals($json->payload, [$data[0], $data[1]]);
            $data = array_slice($data, $to);

        } while($nextIdentify != '-1');
    }
}