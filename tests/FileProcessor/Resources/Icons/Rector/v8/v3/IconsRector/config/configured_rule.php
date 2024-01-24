<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector\v8\v3\IconsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../../../config/config_test.php');
    $rectorConfig->services()
        ->set(IconsRector::class)->tag('typo3_rector.icon_rectors');
};
