<?php

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;

interface PackageResolver
{
    public function findPackage(ComposerPackage $package): ExtensionCollection;

    /**
     * @return ComposerPackage[]
     */
    public function findAllPackagesByType(string $type): array;
}
