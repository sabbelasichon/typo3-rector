<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateSingleDataStructureConfigurationRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveEvalYearFlagRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveFieldSearchConfigOptionsRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveIsStaticControlOptionRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveMaxDBListItemsRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveTcaControlOptionSearchFieldsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateSingleDataStructureConfigurationRector::class);
    $rectorConfig->rule(RemoveEvalYearFlagRector::class);
    $rectorConfig->rule(RemoveFieldSearchConfigOptionsRector::class);
    $rectorConfig->rule(RemoveIsStaticControlOptionRector::class);
    $rectorConfig->rule(RemoveMaxDBListItemsRector::class);
    $rectorConfig->rule(RemoveTcaControlOptionSearchFieldsRector::class);
};
