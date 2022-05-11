<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v12\v0\MigrateInternalTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateInternalTypeRector::class);

    $rectorConfig
        ->ruleWithConfiguration(RenameClassMapAliasRector::class, [
            __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/frontend/Migrations/Code/ClassAliasMap.php',
        ]);
};
