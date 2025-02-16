<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/v10/tca-100.php');
    $rectorConfig->import(__DIR__ . '/v10/tca-104.php');
    $rectorConfig->import(__DIR__ . '/v10/typo3-100.php');
    $rectorConfig->import(__DIR__ . '/v10/typo3-101.php');
    $rectorConfig->import(__DIR__ . '/v10/typo3-102.php');
    $rectorConfig->import(__DIR__ . '/v10/typo3-103.php');
    $rectorConfig->import(__DIR__ . '/v10/typo3-104.php');
    $rectorConfig->import(__DIR__ . '/v10/use-constants-from-typo3-database-connection.php');
};
