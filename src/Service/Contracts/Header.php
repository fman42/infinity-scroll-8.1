<?php

namespace InfinityScrollPagination\Service\Contracts;

interface Header
{
    public function setCountItems(?int $countItems): void;

    public function getCountItems(): ?int;

    public function setIdentifyKey(string $identifyKey): void;

    public function getIdentifyKey(): string;

    public function toArray(): array;

    public function __toString(): string;
}