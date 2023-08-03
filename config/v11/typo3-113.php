<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v11\v3\RemoveBackendUtilityViewOnClickUsageRector;
use Ssch\TYPO3Rector\Rector\v11\v3\ReplaceStdAuthCodeWithHmacRector;
use Ssch\TYPO3Rector\Rector\v11\v3\SubstituteExtbaseRequestGetBaseUriRector;
use Ssch\TYPO3Rector\Rector\v11\v3\SubstituteMethodRmFromListOfGeneralUtilityRector;
use Ssch\TYPO3Rector\Rector\v11\v3\SwitchBehaviorOfArrayUtilityMethodsRector;
use Ssch\TYPO3Rector\Rector\v11\v3\typo3\UseNormalizedParamsToGetRequestUrlRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemoveBackendUtilityViewOnClickUsageRector::class);
    $rectorConfig->rule(ReplaceStdAuthCodeWithHmacRector::class);
    $rectorConfig->rule(SubstituteMethodRmFromListOfGeneralUtilityRector::class);
    $rectorConfig->rule(SwitchBehaviorOfArrayUtilityMethodsRector::class);
    $rectorConfig->rule(SubstituteExtbaseRequestGetBaseUriRector::class);
    $rectorConfig->rule(UseNormalizedParamsToGetRequestUrlRector::class);
};
