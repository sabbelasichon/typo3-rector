<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO313\v1\MigrateGeneralUtilityHmacToHashServiceHmacRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateGeneralUtilityHmacToHashServiceHmacRector::class);
};
