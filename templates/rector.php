<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        Typo3SetList::TYPO3_104,
        Typo3SetList::TYPO3_11,
        Typo3SetList::TYPO3_12,
    ]);

    // Define your target version which you want to support
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
};
