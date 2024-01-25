<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v4\typoscript\MigrateXhtmlDoctypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(MigrateXhtmlDoctypeRector::class)->tag('typo3_rector.typoscript_rectors');
};
