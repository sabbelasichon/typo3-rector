<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Composer\Semver\Semver;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Rector\Composer\ValueObject\PackageAndVersion;
use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Ssch\TYPO3Rector\ValueObject\ReplacePackage;

final class ComposerPackageParser implements PackageParser
{
    public function parsePackage(string $content, ComposerPackage $composerPackage): ExtensionCollection
    {
        $json = Json::decode($content, Json::FORCE_ARRAY);

        $extensionCollection = new ExtensionCollection();

        if (! array_key_exists('packages', $json)) {
            return $extensionCollection;
        }

        if (! array_key_exists((string) $composerPackage, $json['packages'])) {
            return $extensionCollection;
        }

        $packages = $json['packages'][(string) $composerPackage];

        foreach ($packages as $package) {
            if (! array_key_exists('require', $package)) {
                continue;
            }

            if (! is_array($package['require'])) {
                continue;
            }

            if (! array_key_exists('typo3/cms-core', $package['require'])) {
                continue;
            }

            $typo3Versions = [];

            foreach (self::TYPO3_UPPER_BOUNDS as $typo3Version) {
                if (Semver::satisfies($typo3Version, $package['require']['typo3/cms-core'])) {
                    $typo3Versions[] = new Typo3Version($typo3Version);
                }
            }

            if ([] === $typo3Versions) {
                continue;
            }

            $replacePackage = null;
            if (array_key_exists('replace', $package) && is_array($package['replace'])) {
                foreach ($package['replace'] as $replace => $version) {
                    if (Strings::startsWith($replace, 'typo3-ter')) {
                        $replacePackage = new ReplacePackage($replace, (string) $composerPackage);
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
     * @inheritDoc
     */
    public function parsePackages(string $content): array
    {
        $json = Json::decode($content, Json::FORCE_ARRAY);
        if (! array_key_exists('packageNames', $json)) {
            return [];
        }

        return array_map(function (string $package) {
            return new ComposerPackage($package);
        }, $json['packageNames']);
    }
}
