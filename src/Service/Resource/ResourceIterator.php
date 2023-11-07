<?php

namespace InfinityScrollPagination\Service\Resource;

use Countable;
use InfinityScrollPagination\Service\Abstracts\Resource;
use InfinityScrollPagination\Service\Contracts\Request;
use InfinityScrollPagination\Service\Implementation\Meta;
use InfinityScrollPagination\Service\Implementation\Response;
use Iterator;

final class ResourceIterator extends Resource
{
    const NAMESPACE = 'I';
    private Iterator $store;
    private int $counter = 0;

    final public function __construct(Iterator $iterator)
    {
        $this->store = $iterator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function get(Request $request): Response
    {
        $response = new Response();

        $this->applyMeta($response, $request);

        $response->getHeader()->setIdentifyKey($request->getIdentifyKey());

        $payload = [];
        for ($index = 0; $index < $request->getTo() && $this->store->valid(); $index++) {
            $payload[] = $this->store->current();
            $this->store->next();
            $this->counter++;
        }
        $response->setPayload($payload);

        if ($this->store->valid()) {
            $this->applyNextIdentify($response);
        }
        if ($count = count($payload)) {
            $response->getHeader()->setCountItems($count);
        }

        return $response;
    }

    private function applyMeta(Response $response, Request $request)
    {
        if ($this->store instanceof Countable || method_exists($this->store, 'count')) {
            $response->setMeta(new Meta());
            $this->initCounter($request);

            for ($index = 0; $index < $this->counter; $index++) {
                $this->store->next();
            }

            $response->getMeta()->setTotalItems($this->store->count());
        }
    }

    private function applyNextIdentify(Response $response)
    {
        if ($response->getMeta() == null) {
            return;
        }
        $response->getMeta()->setHasNext(true);
        $response->getMeta()->setNextIdentify(
            self::NAMESPACE . ':' . $this->counter
        );
    }
    private function initCounter(Request $request): void
    {
        if ($request->getPage()) {
            $this->counter = $request->getTo() * ($request->getPage() - 1);
        } else {
            $identify = $request->getNextIdentify();
            [$namespace, $counter] = array_merge(explode(':', $identify), ['','']);
            if ($namespace == self::NAMESPACE) {
                $this->counter = $counter;
            }
        }
    }
}