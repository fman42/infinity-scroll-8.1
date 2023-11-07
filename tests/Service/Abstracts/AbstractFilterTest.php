<?php

namespace Tests\Service\Abstracts;

use InfinityScrollPagination\Service\Abstracts\AbstractFilter;
use InfinityScrollPagination\Service\Contracts\Filter;
use PHPUnit\Framework\TestCase;

class AbstractFilterTest extends TestCase
{
    public function test(): void
    {
        $class = new class () extends AbstractFilter {
            private bool $run = false;
            public function test(string $value, string $key): void
            {
                $this->run = true;
            }

            public function isRun(): bool
            {
                return $this->run;
            }
        };
        $class->includeFilter(new __TFilter('test'));
        $this->assertTrue($class->isRun());
    }
}
class __TFilter implements Filter {
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setName(string $name): void
    {
        // TODO: Implement setName() method.
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setIdentifyKey(string $identifyKey): void
    {
        // TODO: Implement setIdentifyKey() method.
    }

    public function getIdentifyKey(): string
    {
        return '';
    }

    public function setValue(string $value): void
    {
        // TODO: Implement setValue() method.
    }

    public function getValue(): string
    {
        return '';
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    public function isEmpty(): bool
    {
        // TODO: Implement isEmpty() method.
    }
};
