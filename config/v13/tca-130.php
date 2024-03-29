<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\RemoveMmHasUidFieldRector::class);
};
