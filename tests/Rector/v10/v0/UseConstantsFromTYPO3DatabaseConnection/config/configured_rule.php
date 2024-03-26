<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->import(__DIR__ . '/../../../../../../config/v10/use-constants-from-typo3-database-connection.php');
};
