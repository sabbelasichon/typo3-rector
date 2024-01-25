<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v10\v0\typoscript\RemoveUseCacheHashRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(RemoveUseCacheHashRector::class)->tag('typo3_rector.typoscript_rectors');
};
