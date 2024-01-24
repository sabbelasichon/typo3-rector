<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v11\v4\RegisterIconToIconFileRector;

return static function (RectorConfig $rectorConfig): void {
    global $services;
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->services()
        ->set(RegisterIconToIconFileRector::class)->tag('typo3_rector.icon_rectors');
};
