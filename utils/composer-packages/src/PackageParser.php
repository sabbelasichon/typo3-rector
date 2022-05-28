<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;

interface PackageParser
{
    public function parsePackage(string $content, ComposerPackage $composerPackage): ExtensionCollection;

    /**
     * @return ComposerPackage[]
     */
    public function parsePackages(string $content): array;
}
