<?php

namespace InfinityScrollPagination\Service\Contracts;

interface Request
{
    public function getIdentifyKey(): ?string;

    public function setIdentifyKey(string $identifyKey): void;

    public function getTo(): ?int;

    public function setTo(int $to): void;

    public function getFilter(): ?array;

    public function setFilter(array $filter): void;

    public function addFilter(Filter $filter): void;

    public function setPage(int $page): void;

    public function getPage(): ?int;

    public function setNextIdentify(string $nextIdentify): void;

    public function getNextIdentify(): ?string;

    public function setSelect(array $select): void;

    public function addSelect(string $select): void;

    public function getSelect(): ?array;

    public function toArray(): array;
}