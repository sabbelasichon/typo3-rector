<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config_test.php');
    $rectorConfig->importNames();

    $rectorConfig
        ->ruleWithConfiguration(RenameClassMapAliasRector::class, [
            __DIR__ . '/../../../../Migrations/TYPO3/9.5/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../../../Migrations/TYPO3/9.5/typo3/sysext/core/Migrations/Code/ClassAliasMap.php',
        ]);
};
