<?php

namespace InfinityScrollPagination\Service\Contracts;

interface Response
{
    public function setPayload(array $payload): void;

    public function getPayload(): array;

    public function setHeader(Header $header): void;

    public function getHeader(): Header;

    public function setMeta(Meta $meta): void;

    public function getMeta(): ?Meta;

    public function toArray(): array;

    public function __toString(): string;
}