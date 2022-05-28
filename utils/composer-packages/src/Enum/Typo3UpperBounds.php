<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Enum;

use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;

final class Typo3UpperBounds
{
    /**
     * @var string[]
     */
    public const VERSIONS = ['8.7.99', '9.5.99', '10.4.99', '11.0.99'];

    /**
     * @return Typo3Version[]
     */
    public static function provide(): array
    {
        $typo3Versions = [];
        foreach (self::VERSIONS as $version) {
            $typo3Versions[] = new Typo3Version($version);
        }

        return $typo3Versions;
    }
}
