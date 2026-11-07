<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v2\MigrateAssetCollectorUseNonceOptionToCspRector;
use Ssch\TYPO3Rector\TYPO314\v2\MigrateFormEngineAdditionalHiddenFieldsRector;
use Ssch\TYPO3Rector\TYPO314\v2\MigrateTcaOptionAllowedRecordTypesForPageTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateAssetCollectorUseNonceOptionToCspRector::class);
    $rectorConfig->rule(MigrateTcaOptionAllowedRecordTypesForPageTypesRector::class);
    $rectorConfig->rule(MigrateFormEngineAdditionalHiddenFieldsRector::class);
};
