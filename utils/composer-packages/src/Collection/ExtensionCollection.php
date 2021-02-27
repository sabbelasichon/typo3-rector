<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Collection;

use Composer\Semver\Semver;
use Countable;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;

final class ExtensionCollection implements Countable
{
    /**
     * @var ExtensionVersion[]
     */
    private $extensions = [];

    public function addExtension(ExtensionVersion $extension): void
    {
        $this->extensions[$extension->version()] = $extension;
    }

    public function count(): int
    {
        return count($this->extensions);
    }

    public function findLowestVersion(Typo3Version $typo3Version): ?ExtensionVersion
    {
        $extensions = Semver::sort($this->extensions);

        foreach ($extensions as $extension) {
            if ($extension->supportsVersion($typo3Version)) {
                return $extension;
            }
        }

        return null;
    }
}
