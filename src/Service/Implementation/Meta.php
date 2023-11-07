<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Contracts\Meta as MetaContract;
use InfinityScrollPagination\Service\Contracts\Page as PageContract;

final class Meta implements MetaContract
{
    private ?PageContract $page = null;
    private bool $hasNext = false;
    private ?string $nextIdentify = null;
    private int $totalItems = 0;

    final public function __construct()
    {
    }

    final public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    final public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    final public function setPage(PageContract $page): void
    {
        $this->page = $page;
    }

    final public function getPage(): ?PageContract
    {
        return $this->page;
    }

    final public function setHasNext(bool $hasNext): void
    {
        $this->hasNext = $hasNext;
    }

    final public function getHasNext(): bool
    {
        return $this->hasNext;
    }

    final public function setNextIdentify(string $nextIdentify): void
    {
        $this->nextIdentify = $nextIdentify;
    }

    final public function getNextIdentify(): ?string
    {
        return $this->nextIdentify;
    }

    final public function toArray(): ?array
    {
        $hasNext = $this->getHasNext();
        $total = $this->getTotalItems();

        if (!$hasNext && $total) {
            $this->setNextIdentify('-1');
        }

        if ($this->getNextIdentify() == null) {
            return null;
        }

        if (!$hasNext || !$total) {
            $this->setNextIdentify('-1');
        }

        $list = [
            'totalItems' => $total,
            'hasNext' => $hasNext,
            'nextIdentify' => $this->getNextIdentify(),
        ];

        if ($this->getPage() != null) {
            $page = $this->getPage()->toArray();
            if ($page != null) {
                $list['page'] = $page;
            }
        }

        return $list;
    }

    final public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}