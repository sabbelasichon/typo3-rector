<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/v11/typo3-110.php');
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
};
