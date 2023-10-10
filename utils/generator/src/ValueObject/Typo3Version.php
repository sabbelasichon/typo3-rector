<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Typo3Version
{
    /**
     * @readonly
     */
    private int $major;

    /**
     * @readonly
     */
    private int $minor;

    private function __construct(int $major, int $minor)
    {
        $this->major = $major;
        $this->minor = $minor;
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
        if (! str_contains($version, '.')) {
            $version .= '.0';
        }

        [$major, $minor] = explode('.', $version, 2);

        return new self((int) $major, (int) $minor);
    }

    public function getFullVersion(): string
    {
        return sprintf('%d%d', $this->major, $this->minor);
    }
}
