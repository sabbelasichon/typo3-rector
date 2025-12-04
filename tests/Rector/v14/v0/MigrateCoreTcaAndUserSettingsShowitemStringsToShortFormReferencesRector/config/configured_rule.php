<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->importNames(false, false);
    $rectorConfig->rule(MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector::class);
};
