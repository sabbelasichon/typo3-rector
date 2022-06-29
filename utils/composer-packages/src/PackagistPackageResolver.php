<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;
use UnexpectedValueException;

final class PackagistPackageResolver
{
    /**
     * @readonly
     */
    private ComposerPackageParser $composerPackageParser;

    public function __construct(ComposerPackageParser $composerPackageParser)
    {
        $this->composerPackageParser = $composerPackageParser;
    }

    public function findPackage(ComposerPackage $composerPackage): ExtensionCollection
    {
        $json = $this->getUrl(sprintf('https://repo.packagist.org/p2/%s.json', $composerPackage));

        return $this->composerPackageParser->parsePackage($json, $composerPackage);
    }

    /**
     * @return ComposerPackage[]
     */
    public function findAllPackagesByType(string $type): array
    {
        $json = $this->getUrl(sprintf('https://packagist.org/packages/list.json?type=%s', $type));

        return $this->composerPackageParser->parsePackages($json);
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
