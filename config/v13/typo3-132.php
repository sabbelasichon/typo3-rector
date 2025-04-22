<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO313\v2\MigrateNamespacedShortHandValidatorRector;
use Ssch\TYPO3Rector\TYPO313\v2\MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector;
use Ssch\TYPO3Rector\TYPO313\v2\RemoveAddRootLineFieldsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector::class);
    $rectorConfig->rule(RemoveAddRootLineFieldsRector::class);
    $rectorConfig->rule(MigrateNamespacedShortHandValidatorRector::class);
};
