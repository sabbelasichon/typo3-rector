<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO312\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO312\v4\MigrateRequestArgumentFromMethodStartRector::class);
};
