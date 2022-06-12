<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\MigrateNullFlagRector;
use Ssch\TYPO3Rector\Rector\v12\v0\MigrateRequiredFlagRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveTCAInterfaceAlwaysDescriptionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateNullFlagRector::class);
    $rectorConfig->rule(MigrateRequiredFlagRector::class);
    $rectorConfig->rule(RemoveTCAInterfaceAlwaysDescriptionRector::class);
};
