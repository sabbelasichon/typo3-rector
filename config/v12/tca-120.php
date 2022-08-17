<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateColsToSizeForTcaTypeNoneRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateInternalTypeRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateNullFlagRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateRequiredFlagRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveCruserIdRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveTCAInterfaceAlwaysDescriptionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateColsToSizeForTcaTypeNoneRector::class);
    $rectorConfig->rule(MigrateInternalTypeRector::class);
    $rectorConfig->rule(MigrateNullFlagRector::class);
    $rectorConfig->rule(MigrateRequiredFlagRector::class);
    $rectorConfig->rule(RemoveTCAInterfaceAlwaysDescriptionRector::class);
    $rectorConfig->rule(RemoveCruserIdRector::class);
};
