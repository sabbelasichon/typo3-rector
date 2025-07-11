<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveEvalYearFlagRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveIsStaticControlOptionRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveMaxDBListItemsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemoveMaxDBListItemsRector::class);
    $rectorConfig->rule(RemoveIsStaticControlOptionRector::class);
    $rectorConfig->rule(RemoveEvalYearFlagRector::class);
};
