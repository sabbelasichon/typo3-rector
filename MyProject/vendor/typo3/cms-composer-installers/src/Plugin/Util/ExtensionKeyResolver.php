<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Composer\Plugin\Util;

use Composer\Package\PackageInterface;

/**
 * Resolves an extension key from a package
 */
class ExtensionKeyResolver
{
    /**
     * Resolves the extension key from extra section
     *
     * @param PackageInterface $package
     * @throws \RuntimeException
     * @return string
     */
    public static function resolve(PackageInterface $package): string
    {
        $extra = $package->getExtra();
        if (empty($extra['typo3/cms']['extension-key']) && str_starts_with($package->getType(), 'typo3-cms-')) {
            // The only reason this is enforced, is to ease the transition of extensions of type "typo3-cms-*"
            // Since the logic is removed how to derive the extension key from package name, we must enforce
            // the extension key to be set to avoid ambiguity of the extension key between previous installers versions and this one
            // e.g. package "foo/bar" previously was resolved to extension key "bar" and would now be resolved to "foo/bar"
            // The only way to avoid this, is to enforce the deprecation and require an extension key to be set.
            // This is important, as dependents of such extensions can reference paths using the key and this key must not
            // differ from different versions of the composer installers package.
            throw new \RuntimeException(sprintf('Extension with package name "%s" does not define an extension key.', $package->getName()), 1501195043);
        }

        return $extra['typo3/cms']['extension-key'] ?? $package->getName();
    }
}
