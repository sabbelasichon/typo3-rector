<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v11\v5\RegisterIconToIconFileRector;
use Ssch\TYPO3Rector\Rector\v11\v5\RegisterIconToIconFileRector\AddIconsToReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->rule(AddIconsToReturnRector::class);
    $rectorConfig->rule(RegisterIconToIconFileRector::class);
};
