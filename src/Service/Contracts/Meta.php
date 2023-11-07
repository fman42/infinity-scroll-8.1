<?php

namespace InfinityScrollPagination\Service\Contracts;

interface Meta
{
    public function setTotalItems(int $totalItems): void;

    public function getTotalItems(): int;

    public function setPage(Page $page): void;

    public function getPage(): ?Page;

    public function setHasNext(bool $hasNext): void;

    public function getHasNext(): bool;

    public function setNextIdentify(string $nextIdentify): void;

    public function getNextIdentify(): ?string;

    public function toArray(): ?array;

    public function __toString(): string;
}