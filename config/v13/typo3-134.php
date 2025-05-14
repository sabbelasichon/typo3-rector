<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesRector;
use Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesSwapArgsRector;
use Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesTCARector;
use Ssch\TYPO3Rector\TYPO313\v4\RemoveTcaSubTypesExcludeListTCARector;
use Ssch\TYPO3Rector\TYPO313\v4\RenameTableOptionsAndCollateConnectionConfigurationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigratePluginContentElementAndPluginSubtypesRector::class);
    $rectorConfig->rule(MigratePluginContentElementAndPluginSubtypesTCARector::class);
    $rectorConfig->rule(RemoveTcaSubTypesExcludeListTCARector::class);
    $rectorConfig->rule(MigratePluginContentElementAndPluginSubtypesSwapArgsRector::class);
    $rectorConfig->rule(RenameTableOptionsAndCollateConnectionConfigurationRector::class);
};
