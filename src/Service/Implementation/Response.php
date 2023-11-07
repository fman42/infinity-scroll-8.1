<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Contracts\Header as HeaderContract;
use InfinityScrollPagination\Service\Contracts\Meta as MetaContract;
use InfinityScrollPagination\Service\Contracts\Response as ResponseContract;

final class Response implements ResponseContract
{
    private array $payload = [];
    private ?HeaderContract $header = null;
    private ?MetaContract $meta = null;

    final public function __construct()
    {
        $this->header = new Header();
    }

    final public function setPayload(array $payload): void
    {
        $this->payload = $payload;

        if ($this->getHeader() != null) {
            $this->getHeader()->setCountItems(count($this->getPayload()));
        }
    }

    final public function getPayload(): array
    {
        return $this->payload;
    }

    final public function setHeader(HeaderContract $header): void
    {
        $this->header = $header;
    }

    final public function getHeader(): HeaderContract
    {
        return $this->header;
    }

    final public function setMeta(MetaContract $meta): void
    {
        $this->meta = $meta;
    }

    final public function getMeta(): ?MetaContract
    {
        return $this->meta;
    }

    final public function toArray(): array
    {
        $list = [
            'payload' => $this->getPayload(),
            'header' => $this->getHeader()->toArray()
        ];

        if ($this->getMeta() == null) {
            $list['meta'] = null;
        } else {
            $meta = $this->getMeta()->toArray();
            if ($meta == null) {
                $list['meta'] = null;
            } else {
                $list['meta'] = $meta;
            }
        }

        return $list;
    }

    final public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}