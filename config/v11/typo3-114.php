<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO311\v4\AddSetConfigurationMethodToExceptionHandlerRector;
use Ssch\TYPO3Rector\TYPO311\v4\ProvideCObjViaMethodRector;
use Ssch\TYPO3Rector\TYPO311\v4\RegisterIconToIconFileRector;
use Ssch\TYPO3Rector\TYPO311\v4\UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector::class);
    $rectorConfig->rule(ProvideCObjViaMethodRector::class);
    $rectorConfig->rule(AddSetConfigurationMethodToExceptionHandlerRector::class);
    $rectorConfig->rule(RegisterIconToIconFileRector::class);
};
