<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Webmozart\Assert\Assert;

final class Description
{
    /**
     * @readonly
     */
    private string $description;

    private function __construct(string $description)
    {
        Assert::maxLength($description, 120);
        Assert::minLength($description, 5);
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
