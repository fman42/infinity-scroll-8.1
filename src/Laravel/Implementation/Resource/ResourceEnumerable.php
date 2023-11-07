<?php

namespace InfinityScrollPagination\Laravel\Implementation\Resource;

use Illuminate\Http\Response;
use Illuminate\Support\Enumerable;
use InfinityScrollPagination\Laravel\Abstracts\FormRequestDecorator;
use InfinityScrollPagination\Laravel\Abstracts\Resource;
use InfinityScrollPagination\Service\Implementation\Meta;
use InfinityScrollPagination\Service\Implementation\Page;
use InfinityScrollPagination\Service\Implementation\Response as ResponseImpl;

class ResourceEnumerable extends Resource
{
    const NAMESPACE = 'E';
    private Enumerable $store;
    private int $counter = 0;

    public function __construct(Enumerable $enumerable)
    {
        $this->store = $enumerable;
    }

    public function get(FormRequestDecorator $request): Response
    {
        $response = new ResponseImpl();

        $this->setCounter($response, $request);
        $this->setPayload($response, $request);
        $this->setHeader($response, $request);
        $this->setMeta($response, $request);
        $this->setNextIdentify($response, $request);

        return new Response($response->toArray(), 200);
    }

    private function setHeader(ResponseImpl $response, FormRequestDecorator $request)
    {
        $response->getHeader()->setIdentifyKey($request->getIdentifyKey());
        $response->getHeader()->setCountItems($count = count($response->getPayload()));
    }

    private function setPayload(ResponseImpl $response, FormRequestDecorator $request)
    {
        try {
            $iterator = $this->store->skip($this->counter)->getIterator();
            $payload = [];
            for($index = 0; $index < $request->getTo() && $iterator->valid(); $index++) {
                $payload[] = $iterator->current();
                $iterator->next();
                $this->counter++;
            }
            $response->setPayload($payload);
        } catch (\Exception $exception) {
            $response->setPayload([]);
        }
    }

    private function setCounter(ResponseImpl $response, FormRequestDecorator $request)
    {
        if ($page = $request->getPage()) {
            $this->counter = $request->getTo() * ($page - 1);
        } else {
            $identify = $request->getNextIdentify();
            [$namespace, $counter] = array_merge(explode(':', $identify), ['','']);
            if ($namespace == self::NAMESPACE) {
                $this->counter = $counter;
            }
        }
    }

    private function setMeta(ResponseImpl $response, FormRequestDecorator $request)
    {
        $response->setMeta(new Meta());
        $response->getMeta()->setTotalItems($total = $this->store->count());

        if ($request->getPage()) {
            $response->getMeta()->setPage($page = new Page());
            $page->setTotalPages(round($total / $request->getTo()));
            $page->setCurrentPage($request->getPage());
        }
    }

    private function setNextIdentify(ResponseImpl $response, FormRequestDecorator $request)
    {
        if ($this->counter < $response->getMeta()->getTotalItems()) {
            $response->getMeta()->setHasNext(true);
            $response->getMeta()->setNextIdentify(
                self::NAMESPACE . ':' . $this->counter
            );
        }
    }
}