<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;

interface PackageParser
{
    /**
     * @var array
     */
    public const TYPO3_UPPER_BOUNDS = ['8.7.99', '9.5.99', '10.4.99', '11.0.99'];

    public function parsePackage(string $content, ComposerPackage $composerPackage): ExtensionCollection;

    /**
     * @return ComposerPackage[]
     */
    public function parsePackages(string $content): array;
}
