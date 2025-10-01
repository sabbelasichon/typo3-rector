<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector;
use Ssch\TYPO3Rector\TYPO314\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateEnvironmentGetComposerRootPathRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateObsoleteCharsetInSanitizeFileNameRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(DropFifthParameterForExtensionUtilityConfigurePluginRector::class);
    $rectorConfig->rule(ExtendExtbaseValidatorsFromAbstractValidatorRector::class);
    $rectorConfig->rule(MigrateEnvironmentGetComposerRootPathRector::class);
    $rectorConfig->rule(MigrateObsoleteCharsetInSanitizeFileNameRector::class);
};
