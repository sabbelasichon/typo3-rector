<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/v12/tca-120.php');
    $rectorConfig->import(__DIR__ . '/v12/tca-123.php');
    $rectorConfig->import(__DIR__ . '/v12/typo3-120.php');
    $rectorConfig->import(__DIR__ . '/v12/typo3-123.php');
};
