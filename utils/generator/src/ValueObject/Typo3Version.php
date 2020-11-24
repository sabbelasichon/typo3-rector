<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Webmozart\Assert\Assert;

final class Typo3Version
{
    /**
     * @var int
     */
    private $major;

    /**
     * @var int
     */
    private $minor;

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
        Assert::contains($version, '.');

        [$major, $minor] = explode('.', $version, 2);

        return new self((int) $major, (int) $minor);
    }
}
