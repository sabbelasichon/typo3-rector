<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v9\v0\DatabaseConnectionToDbalRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/config.php');
    $rectorConfig->rule(DatabaseConnectionToDbalRector::class);
};
