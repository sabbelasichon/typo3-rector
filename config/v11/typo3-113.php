<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO311\v3\MigrateHttpUtilityRedirectRector;
use Ssch\TYPO3Rector\TYPO311\v3\SubstituteExtbaseRequestGetBaseUriRector;
use Ssch\TYPO3Rector\TYPO311\v3\SubstituteMethodRmFromListOfGeneralUtilityRector;
use Ssch\TYPO3Rector\TYPO311\v3\SwitchBehaviorOfArrayUtilityMethodsRector;
use Ssch\TYPO3Rector\TYPO311\v3\UseNormalizedParamsToGetRequestUrlRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(SubstituteMethodRmFromListOfGeneralUtilityRector::class);
    $rectorConfig->rule(SwitchBehaviorOfArrayUtilityMethodsRector::class);
    $rectorConfig->rule(SubstituteExtbaseRequestGetBaseUriRector::class);
    $rectorConfig->rule(UseNormalizedParamsToGetRequestUrlRector::class);
    $rectorConfig->rule(MigrateHttpUtilityRedirectRector::class);
};
