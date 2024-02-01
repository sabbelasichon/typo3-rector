<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/v11/tca-110.php');
    $rectorConfig->import(__DIR__ . '/v11/tca-113.php');
    $rectorConfig->import(__DIR__ . '/v11/tca-114.php');
    $rectorConfig->import(__DIR__ . '/v11/tca-115.php');
    $rectorConfig->import(__DIR__ . '/v11/typo3-110.php');
    $rectorConfig->import(__DIR__ . '/v11/typo3-112.php');
    $rectorConfig->import(__DIR__ . '/v11/typo3-113.php');
    $rectorConfig->import(__DIR__ . '/v11/typo3-114.php');
    $rectorConfig->import(__DIR__ . '/v11/typo3-115.php');
};
