<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Name
{
    /**
     * @readonly
     */
    private string $name;

    private function __construct(string $name)
    {
        $this->name = ucfirst($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRectorName(): string
    {
        return sprintf('%sRector', $this->name);
    }

    public static function createFromString(string $name): self
    {
        return new self($name);
    }
}
