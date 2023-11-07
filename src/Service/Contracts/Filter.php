<?php

namespace InfinityScrollPagination\Service\Contracts;

interface Filter
{
    public function setName(string $name): void;

    public function getName(): string;

    public function setIdentifyKey(string $identifyKey): void;

    public function getIdentifyKey(): string;

    public function setValue(string $value): void;

    public function getValue(): string;

    public function toArray(): ?array;

    public function isEmpty(): bool;
}