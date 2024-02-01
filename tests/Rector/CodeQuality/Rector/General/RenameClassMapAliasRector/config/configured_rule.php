<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\CodeQuality\General\RenameClassMapAliasRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->importNames();

    $rectorConfig
        ->ruleWithConfiguration(RenameClassMapAliasRector::class, [
            __DIR__ . '/../../../../../../../Migrations/TYPO3/10.4/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
        ]);
};
