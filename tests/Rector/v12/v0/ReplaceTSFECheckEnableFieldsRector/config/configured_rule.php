<?php

declare(strict_types=1);
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector;

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->rule(ReplaceTSFECheckEnableFieldsRector::class);
};
