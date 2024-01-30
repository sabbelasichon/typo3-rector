<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\TYPO312\v0\RemoveTCAInterfaceAlwaysDescriptionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config_test.php');
    $rectorConfig->rule(RemoveTCAInterfaceAlwaysDescriptionRector::class);
};
