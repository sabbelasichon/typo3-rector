<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\CodeQuality\General\RemoveTypo3VersionChecksRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->ruleWithConfiguration(
        RemoveTypo3VersionChecksRector::class,
        [
            RemoveTypo3VersionChecksRector::TARGET_VERSION => 13,
        ]
    );
};
