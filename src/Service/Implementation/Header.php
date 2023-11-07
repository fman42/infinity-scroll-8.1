<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Contracts\Header as HeaderContract;

final class Header implements HeaderContract
{
    private ?int $countItems = null;
    private string $identifyKey = "";

    final public function __construct()
    {
    }

    final public function setCountItems(?int $countItems): void
    {
        $this->countItems = $countItems;
    }

    final public function getCountItems(): ?int
    {
        return $this->countItems;
    }

    final public function setIdentifyKey(string $identifyKey): void
    {
        $this->identifyKey = $identifyKey;
    }

    final public function getIdentifyKey(): string
    {
        return $this->identifyKey;
    }

    final public function toArray(): array
    {
        if ($this->getCountItems() != null) {
            return [
                'countItems' => $this->getCountItems(),
                'identifyKey' => $this->getIdentifyKey()
            ];
        }

        return [
            'identifyKey' => $this->getIdentifyKey()
        ];
    }

    final public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}