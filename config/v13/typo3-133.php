<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO313\v3\MigrateFluidStandaloneMethodsRector;
use Ssch\TYPO3Rector\TYPO313\v3\MigrateViewHelperRenderStaticRector;
use Ssch\TYPO3Rector\TYPO313\v3\UseTYPO3CoreViewInterfaceInExtbaseRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateViewHelperRenderStaticRector::class);
    $rectorConfig->rule(UseTYPO3CoreViewInterfaceInExtbaseRector::class);
    $rectorConfig->rule(MigrateFluidStandaloneMethodsRector::class);
};
