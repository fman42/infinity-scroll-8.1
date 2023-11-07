<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Contracts\Filter as FilterContract;
use InfinityScrollPagination\Service\Contracts\Request as RequestContract;

final class Request implements RequestContract
{
    const DEF_TO = 25;
    const MAX_TO = 50;

    private ?string $identifyKey;
    private int $to;
    private ?int $page;
    private string $nextIdentify;
    private ?array $select = null;
    private ?array $filter = null;
    private array $request = [];

    final public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function getDefaultIdentifyKey(?string $key): string
    {
        return $key ?: 'id';
    }

    final public function getIdentifyKey(): ?string
    {
        if (isset($this->identifyKey)) {
            return $this->identifyKey;
        }

        $identifyKey = $this->request['identifyKey'] ?? 'id';
        if (is_string($identifyKey) || is_null($identifyKey)) {
            $this->identifyKey = $this->getDefaultIdentifyKey($identifyKey);
        }

        return $this->identifyKey;
    }

    final public function setIdentifyKey(string $identifyKey): void
    {
        $this->identifyKey = $identifyKey;
    }

    final public function getTo(): ?int
    {
        if (isset($this->to)) {
            return $this->to;
        }

        $to = is_numeric($to = $this->request['to'] ?? self::DEF_TO) ? intval($to) : self::DEF_TO;

        if (-1 <= $to && $to <= self::MAX_TO) {
            return $this->to = $to;
        }

        return $this->to = $to > self::MAX_TO ? self::MAX_TO : -1;
    }

    final public function setTo(int $to): void
    {
        $this->to = $to;
    }

    final public function getFilter(): ?array
    {
        if ($this->filter == null) {
            try {
                $filter = $this->request['filter'] ?? [];
                $this->setFilter(is_array($filter) ? $filter : []);
            } catch (\Exception $ignore) {
                $this->setFilter([]);
            }
        }

        return $this->filter ?: null;
    }

    final public function setFilter(array $filter): void
    {
        $this->filter = [];
        foreach ($filter as $item) {
            if ($item instanceof Filter) {
                if (!$item->isEmpty()) {
                    $this->addFilter($item);
                }
            } elseif (is_array($item) && isset($item['name'], $item['value'], $item['identifyKey'])) {
                $filterOptions = new Filter();

                $filterOptions->setName($item['name']);
                $filterOptions->setValue($item['value']);
                $filterOptions->setIdentifyKey($item['identifyKey']);

                $this->addFilter($filterOptions);
            }
        }
    }

    final public function addFilter(FilterContract $filter): void
    {
        if ($this->filter == null) {
            $this->filter = [];
        }
        $this->filter[] = $filter;
    }

    final public function setPage(int $page): void
    {
        $this->page = $page;
    }

    final public function getPage(): ?int
    {
        if (isset($this->page)) {
            return $this->page;
        }

        $page = $this->request['page'] ?? null;
        $this->page = is_numeric($page) ? $page : null;

        return $this->page;
    }

    final public function setNextIdentify(string $nextIdentify): void
    {
        $this->nextIdentify = $nextIdentify;
    }

    final public function getNextIdentify(): ?string
    {
        if (isset($this->nextIdentify)) {
            return $this->nextIdentify;
        }
        $nextIdentify = $this->request['nextIdentify'] ?? '';
        $this->nextIdentify = is_string($nextIdentify) ? $nextIdentify : '';

        return $this->nextIdentify;
    }

    final public function setSelect(array $select): void
    {
        foreach ($select as $column) {
            if (is_string($column)) {
                $this->addSelect($column);
            }
        }
    }

    final public function addSelect(string $select): void
    {
        $this->select[] = $select;
    }

    final public function getSelect(): ?array
    {
        if ($this->select == null) {
            try {
                $select = $this->request['select'] ?? [];
                $this->setSelect(is_array($select) ? $select : []);
            } catch (\Exception $ignore) {
                $this->setSelect([]);
            }
        }

        return $this->select ?: null;
    }

    final public function toArray(): array
    {
        $list = [];
        if ($this->getIdentifyKey()) {
            $list["identifyKey"] = $this->getIdentifyKey();
        }
        if ($this->getTo()) {
            $list["to"] = $this->getTo();
            if ($this->getPage()) {
                $list["page"] = $this->getPage();
            }
        }

        if ($this->getFilter()) {
            $list["filter"] = array_map(function (FilterContract $filter) {
                return $filter->toArray();
            }, $this->getFilter());
        }

        if ($this->getNextIdentify()) {
            $list["nextIdentify"] = $this->getNextIdentify();
        }

        if ($this->getSelect()) {
            $list["select"] = $this->getSelect();
        }

        return $list;
    }

    final public function __toString()
    {
        return json_encode($this->toArray());
    }
}