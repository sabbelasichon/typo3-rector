<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Nette\Utils\Strings;
use Symplify\SmartFileSystem\SmartFileInfo;

trait FileHelperTrait
{
    private function isExtLocalConf(SmartFileInfo $fileInfo): bool
    {
        return Strings::endsWith($fileInfo->getFilename(), 'ext_localconf.php');
    }

    private function isExtTables(SmartFileInfo $fileInfo): bool
    {
        return Strings::endsWith($fileInfo->getFilename(), 'ext_tables.php');
    }

    private function isExtEmconf(SmartFileInfo $fileInfo): bool
    {
        return Strings::endsWith($fileInfo->getFilename(), 'ext_emconf.php');
    }
}
