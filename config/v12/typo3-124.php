<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\v4\CommandConfigurationToAttributeRector;
use Ssch\TYPO3Rector\TYPO312\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector;
use Ssch\TYPO3Rector\TYPO312\v4\MigrateRequestArgumentFromMethodStartRector;
use Ssch\TYPO3Rector\TYPO312\v4\MigrateTypoScriptFrontendControllerTypeRector;
use Ssch\TYPO3Rector\TYPO312\v4\UseServerRequestInsteadOfGeneralUtilityGetRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateRecordTooltipMethodToRecordIconAltTextMethodRector::class);
    $rectorConfig->rule(MigrateRequestArgumentFromMethodStartRector::class);
    $rectorConfig->rule(CommandConfigurationToAttributeRector::class);
    $rectorConfig->rule(MigrateTypoScriptFrontendControllerTypeRector::class);
    $rectorConfig->rule(UseServerRequestInsteadOfGeneralUtilityGetRector::class);
};
