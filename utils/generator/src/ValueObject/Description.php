<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Description
{
    /**
     * @readonly
     */
    private string $description;

    private function __construct(string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public static function createFromString(string $description): self
    {
        return new self($description);
    }
}
