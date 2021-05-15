<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Webmozart\Assert\Assert;

final class Typo3Version
{
    private function __construct(
        private int $major,
        private int $minor
    ) {
    }

    public function getMajor(): int
    {
        return $this->major;
    }

    public function getMinor(): int
    {
        return $this->minor;
    }

    public static function createFromString(string $version): self
    {
        Assert::contains($version, '.');

        [$major, $minor] = explode('.', $version, 2);

        return new self((int) $major, (int) $minor);
    }

    public function getFullVersion(): string
    {
        return sprintf('%d%d', $this->major, $this->minor);
    }
}
