<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO310\v0\BackendUtilityGetViewDomainToPageRouterRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->rule(BackendUtilityGetViewDomainToPageRouterRector::class);
};
