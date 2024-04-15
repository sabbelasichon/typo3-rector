<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->import(
        __DIR__ . '/../../../../../../config/v13/typo3-130-extbase-hash-service-core-hash-service.php'
    );
};
