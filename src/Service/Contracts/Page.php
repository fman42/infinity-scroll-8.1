<?php

namespace InfinityScrollPagination\Service\Contracts;

interface Page
{
    public function setTotalPages(int $totalPages): void;

    public function getTotalPages(): int;

    public function setCurrentPage(int $currentPage): void;

    public function getCurrentPage(): int;

    public function setNextPage(int $nextPage): void;

    public function getNextPage(): int;

    public function setPrevPage(int $prevPage): void;

    public function getPrevPage(): int;

    public function toArray(): ?array;

    public function __toString(): string;
}