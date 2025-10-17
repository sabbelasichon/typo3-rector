<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\v3\MigrateExtensionManagementUtilityAddTcaSelectItemRector;
use Ssch\TYPO3Rector\TYPO312\v3\MigrateGeneralUtilityGPRector;
use Ssch\TYPO3Rector\TYPO312\v3\MigrateMagicRepositoryMethodsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateExtensionManagementUtilityAddTcaSelectItemRector::class);
    $rectorConfig->rule(MigrateGeneralUtilityGPRector::class);
    $rectorConfig->rule(MigrateMagicRepositoryMethodsRector::class);
};
