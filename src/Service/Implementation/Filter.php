<?php

namespace InfinityScrollPagination\Service\Implementation;

use InfinityScrollPagination\Service\Contracts\Filter as FilterContract;

final class Filter implements FilterContract
{
    private string $name;

    private string $identifyKey;
    private string $value;

    final public function __construct()
    {}

    final public function setName(string $name): void
    {
        $this->name = $name;
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function setIdentifyKey(string $identifyKey): void
    {
        $this->identifyKey = $identifyKey;
    }

    final public function getIdentifyKey(): string
    {
        return $this->identifyKey;
    }

    final public function setValue(string $value): void
    {
        $this->value = $value;
    }

    final public function getValue(): string
    {
        return $this->value;
    }

    final public function toArray(): ?array
    {
        if ($this->isEmpty()) {
            return null;
        }
        return [
            "name" => $this->getName(),
            "identifyKey" => $this->getIdentifyKey(),
            "value" => $this->getValue(),
        ];
    }

    final public function isEmpty(): bool
    {
        return !isset($this->name, $this->value, $this->identifyKey);
    }

    final public function __toString()
    {
        return json_encode($this->toArray());
    }
}