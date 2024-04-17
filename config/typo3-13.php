<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/v13/introduce-capabilities-bit-set.php');
    $rectorConfig->import(__DIR__ . '/v13/strict-types.php');
    $rectorConfig->import(__DIR__ . '/v13/tca-130.php');
    $rectorConfig->import(__DIR__ . '/v13/typo3-130.php');
    $rectorConfig->import(__DIR__ . '/v13/typo3-130-extbase-hash-service-core-hash-service.php');
};
