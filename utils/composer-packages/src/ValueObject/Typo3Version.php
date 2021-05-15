<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\ValueObject;

use Stringable;

final class Typo3Version implements Stringable
{
    public function __construct(private string $version)
    {
    }

    public function __toString(): string
    {
        return $this->version;
    }

    public function getFullVersion(): string
    {
        $typo3Version = \Ssch\TYPO3Rector\Generator\ValueObject\Typo3Version::createFromString(
            substr($this->version, 0, -3)
        );
        return $typo3Version->getFullVersion();
    }
}
