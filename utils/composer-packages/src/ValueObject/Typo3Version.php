<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\ValueObject;

use Stringable;

final class Typo3Version implements Stringable
{
    /**
     * @var string
     */
    private $version;

    public function __construct(string $version)
    {
        $this->version = $version;
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
