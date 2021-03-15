<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\ValueObject;

use Composer\Semver\Semver;
use Rector\Composer\ValueObject\PackageAndVersion;
use Ssch\TYPO3Rector\ValueObject\ReplacePackage;
use Stringable;
use Webmozart\Assert\Assert;

final class ExtensionVersion implements Stringable
{
    /**
     * @var PackageAndVersion
     */
    private $packageAndVersion;

    /**
     * @var Typo3Version[]
     */
    private $typo3Versions;

    /**
     * @var ReplacePackage|null
     */
    private $replacePackage;

    /**
     * @param Typo3Version[] $typo3Versions
     */
    public function __construct(
        PackageAndVersion $packageAndVersion,
        array $typo3Versions,
        ?ReplacePackage $replacePackage = null
    ) {
        Assert::allIsInstanceOf($typo3Versions, Typo3Version::class);
        $this->packageAndVersion = $packageAndVersion;
        $this->typo3Versions = $typo3Versions;
        $this->replacePackage = $replacePackage;
    }

    public function __toString(): string
    {
        return $this->version();
    }

    public function supportsVersion(Typo3Version $typo3Version): bool
    {
        return in_array($typo3Version, $this->typo3Versions, false);
    }

    public function highestSupportedTypo3Version(): Typo3Version
    {
        return Semver::rsort($this->typo3Versions)[0];
    }

    public function version(): string
    {
        return $this->packageAndVersion->getVersion();
    }

    public function packageName(): string
    {
        return $this->packageAndVersion->getPackageName();
    }

    public function getReplacePackage(): ?ReplacePackage
    {
        return $this->replacePackage;
    }

    public function equals(self $extensionVersion): bool
    {
        return $extensionVersion->version() === $this->version() && $this->packageName() === $extensionVersion->packageName();
    }
}
