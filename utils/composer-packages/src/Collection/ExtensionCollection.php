<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Collection;

use ArrayIterator;
use Composer\Semver\Comparator;
use Composer\Semver\Semver;
use Composer\Semver\VersionParser;
use Countable;
use IteratorAggregate;
use Rector\Composer\ValueObject\RenamePackage;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;

/**
 * @see \Ssch\TYPO3Rector\ComposerPackages\Tests\Collection\ExtensionCollectionTest
 */
final class ExtensionCollection implements Countable, IteratorAggregate
{
    private static ?VersionParser $versionParser = null;

    /**
     * @var ExtensionVersion[]
     */
    private array $extensions = [];

    public function addExtension(ExtensionVersion $extension): void
    {
        $this->extensions[$extension->version()] = $extension;
    }

    public function count(): int
    {
        return count($this->extensions);
    }

    public function findHighestVersion(Typo3Version $typo3Version): ?ExtensionVersion
    {
        if (null === self::$versionParser) {
            self::$versionParser = new VersionParser();
        }

        /** @var ExtensionVersion[] $extensions */
        $extensions = Semver::rsort($this->extensions);

        $supportedVersion = null;
        $currentVersion = null;

        foreach ($extensions as $extension) {
            if ($extension->supportsVersion($typo3Version)) {
                $supportedVersion = $extension;
                break;
            }

            $currentVersion = $extension;
        }

        if (null === $supportedVersion) {
            return null;
        }

        if (null === $currentVersion) {
            return $supportedVersion;
        }

        // If the currentVersion supports a lower version of TYPO3 something has been configured wrongly in previous composer.json
        $highestTypo3VersionOfSupportedExtension = self::$versionParser->normalize(
            (string) $supportedVersion->highestSupportedTypo3Version()
        );
        $highestTypo3VersionOfCurrentExtension = self::$versionParser->normalize(
            (string) $currentVersion->highestSupportedTypo3Version()
        );

        if (Comparator::lessThan($highestTypo3VersionOfCurrentExtension, $highestTypo3VersionOfSupportedExtension)) {
            return null;
        }

        return $supportedVersion;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->extensions);
    }

    public function getReplacePackages(): array
    {
        $replacePackages = [];
        foreach ($this->extensions as $extension) {
            $replacePackage = $extension->getReplacePackage();
            if ($replacePackage instanceof RenamePackage) {
                $replacePackages[$replacePackage->getOldPackageName()] = $replacePackage;
            }
        }
        return $replacePackages;
    }
}
