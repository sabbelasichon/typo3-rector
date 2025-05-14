<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO313\v4\RenameTableOptionsAndCollateConnectionConfigurationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->rule(RenameTableOptionsAndCollateConnectionConfigurationRector::class);
};
