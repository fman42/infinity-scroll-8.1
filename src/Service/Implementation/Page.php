<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Contracts\Page as PageContract;

final class Page implements PageContract
{
    private int $totalPages = 0;
    private int $currentPage = 0;
    private int $nextPage = 0;
    private int $prevPage = 0;

    final public function __construct()
    {
    }

    final public function setTotalPages(int $totalPages): void
    {
        $this->totalPages = max(0, $totalPages);
        if ($this->totalPages && $this->getCurrentPage() == 0) {
            $this->setCurrentPage(1);
        }
    }

    final public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    final public function setCurrentPage(int $currentPage): void
    {
        if ($this->getTotalPages() <= 0) {
            return;
        }
        if ($currentPage > $this->getTotalPages()) {
            $currentPage = $this->getTotalPages();
        }

        $currentPage = max($currentPage, 1);

        $this->currentPage = $currentPage;

        $this->setNextPage(min($this->getCurrentPage() + 1, $this->getTotalPages()));

        $this->setPrevPage(max($this->getCurrentPage() - 1, 0));
    }

    final public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    final public function setNextPage(int $nextPage): void
    {
        $this->nextPage = $nextPage;
    }

    final public function getNextPage(): int
    {
        return $this->nextPage;
    }

    final public function setPrevPage(int $prevPage): void
    {
        $this->prevPage = $prevPage;
    }

    final public function getPrevPage(): int
    {
        return $this->prevPage;
    }

    final public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    final public function toArray(): ?array
    {
        $list = [];
        if (!$this->getTotalPages()) {
            return null;
        }

        $list['totalPages'] = $this->getTotalPages();
        $list['currentPage'] = $this->getCurrentPage();

        if ($this->getNextPage() < $this->getTotalPages() && $this->getNextPage() > 0) {
            $list['nextPage'] = $this->getNextPage();
        }
        if ($this->getPrevPage() > 0) {
            $list['prevPage'] = $this->getPrevPage();
        }

        return $list;
    }
}