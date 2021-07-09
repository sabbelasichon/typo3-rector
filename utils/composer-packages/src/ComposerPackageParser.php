<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Composer\Semver\Semver;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Composer\ValueObject\RenamePackage;
use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;

/**
 * @see \Ssch\TYPO3Rector\ComposerPackages\Tests\ComposerPackageParserTest
 */
final class ComposerPackageParser implements PackageParser
{
    /**
     * @var string
     */
    private const PACKAGES = 'packages';

    /**
     * @var string
     */
    private const REPLACE = 'replace';

    public function parsePackage(string $content, ComposerPackage $composerPackage): ExtensionCollection
    {
        $json = Json::decode($content, Json::FORCE_ARRAY);

        $extensionCollection = new ExtensionCollection();

        if (! array_key_exists(self::PACKAGES, $json)) {
            return $extensionCollection;
        }

        if (! array_key_exists((string) $composerPackage, $json[self::PACKAGES])) {
            return $extensionCollection;
        }

        $packages = $json[self::PACKAGES][(string) $composerPackage];

        foreach ($packages as $package) {
            if (! array_key_exists('require', $package)) {
                continue;
            }

            if (! is_array($package['require'])) {
                continue;
            }

            $typo3RequiredPackage = $this->extractTypo3RequiredPackage($package['require']);

            if (null === $typo3RequiredPackage) {
                continue;
            }

            $typo3Versions = [];

            foreach (self::TYPO3_UPPER_BOUNDS as $typo3Version) {
                if (Semver::satisfies($typo3Version, $package['require'][$typo3RequiredPackage])) {
                    $typo3Versions[] = new Typo3Version($typo3Version);
                }
            }

            if ([] === $typo3Versions) {
                continue;
            }

            $replacePackage = null;
            if (array_key_exists(self::REPLACE, $package) && is_array($package[self::REPLACE])) {
                foreach (array_keys($package[self::REPLACE]) as $replace) {
                    if (\str_starts_with($replace, 'typo3-ter')) {
                        $replacePackage = new RenamePackage($replace, (string) $composerPackage);
                        break;
                    }
                }
            }

            $extensionCollection->addExtension(new ExtensionVersion(
                new PackageAndVersion((string) $composerPackage, $package['version']),
                $typo3Versions,
                $replacePackage
            ));
        }

        return $extensionCollection;
    }

    /**
     * @return ComposerPackage[]
     */
    public function parsePackages(string $content): array
    {
        $json = Json::decode($content, Json::FORCE_ARRAY);
        if (! array_key_exists('packageNames', $json)) {
            return [];
        }

        return array_map(fn(string $package): ComposerPackage => new ComposerPackage($package), $json['packageNames']);
    }

    /**
     * @param array<string, string> $require
     */
    private function extractTypo3RequiredPackage(array $require): ?string
    {
        foreach (array_keys($require) as $packageRequire) {
            if (!\str_contains($packageRequire, 'typo3/cms-')) {
                continue;
            }
            return $packageRequire;
        }

        return null;
    }
}
