<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Symplify\SmartFileSystem\Exception\FileNotFoundException;
use Symplify\SmartFileSystem\SmartFileInfo;

final class Typo3SetListComposerConfigurationPathResolver
{
    public function resolveByTypo3Version(Typo3Version $typo3Version): ?SmartFileInfo
    {
        $constant = sprintf(
            '%s::COMPOSER_PACKAGES_%s_EXTENSIONS',
            Typo3SetList::class,
            $typo3Version->getFullVersion()
        );

        if (! defined($constant)) {
            return null;
        }

        try {
            return new SmartFileInfo(constant($constant));
        } catch (FileNotFoundException $fileNotFoundException) {
            return null;
        }
    }

    public function replacePackages(): ?SmartFileInfo
    {
        $constant = sprintf('%s::COMPOSER_PACKAGES_TER_TO_PACKAGIST', Typo3SetList::class);

        if (! defined($constant)) {
            return null;
        }

        try {
            return new SmartFileInfo(constant($constant));
        } catch (FileNotFoundException $fileNotFoundException) {
            return null;
        }
    }
}
