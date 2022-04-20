<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        Typo3SetList::TYPO3_76,
        Typo3SetList::TYPO3_87,
        Typo3SetList::TYPO3_95,
        Typo3SetList::TYPO3_104,
        Typo3SetList::TYPO3_11,
    ]);

    // Define your target version which you want to support
    $rectorConfig->phpVersion(PhpVersion::PHP_74);
};
