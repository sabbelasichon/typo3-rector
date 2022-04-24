<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;
use UnexpectedValueException;

final class PackagistPackageResolver implements PackageResolver
{
    public function __construct(
        private readonly PackageParser $packageParser
    ) {
    }

    public function findPackage(ComposerPackage $package): ExtensionCollection
    {
        $json = $this->getUrl(sprintf('https://repo.packagist.org/p2/%s.json', $package));

        return $this->packageParser->parsePackage($json, $package);
    }

    public function findAllPackagesByType(string $type): array
    {
        $json = $this->getUrl(sprintf('https://packagist.org/packages/list.json?type=%s', $type));

        return $this->packageParser->parsePackages($json);
    }

    private function getUrl(string $url): string
    {
        $content = file_get_contents($url);

        if (false === $content) {
            throw new UnexpectedValueException(sprintf('Could not get content of url %s', $url));
        }

        return $content;
    }
}
