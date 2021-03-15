<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages;

use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Symplify\SmartFileSystem\SmartFileInfo;

interface ComposerConfigurationPathResolver
{
    public function resolveByTypo3Version(Typo3Version $typo3Version): ?SmartFileInfo;

    public function replacePackages(): ?SmartFileInfo;
}
