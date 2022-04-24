<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\ValueObject;

use Composer\Semver\Semver;
use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Composer\ValueObject\RenamePackage;
use Stringable;
use Webmozart\Assert\Assert;

final class ExtensionVersion implements Stringable
{
    /**
     * @var Typo3Version[]
     */
    private array $typo3Versions = [];

    /**
     * @param Typo3Version[] $typo3Versions
     */
    public function __construct(
        private readonly PackageAndVersion $packageAndVersion,
        array $typo3Versions,
        private readonly ?RenamePackage $replacePackage = null
    ) {
        Assert::allIsInstanceOf($typo3Versions, Typo3Version::class);
        $this->typo3Versions = $typo3Versions;
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

    public function getRenamePackage(): ?RenamePackage
    {
        return $this->replacePackage;
    }

    public function equals(self $extensionVersion): bool
    {
        return $extensionVersion->version() === $this->version() && $this->packageName() === $extensionVersion->packageName();
    }
}
