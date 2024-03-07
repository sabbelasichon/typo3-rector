<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\v0\IgnorePageTypeRestrictionRector;
use Ssch\TYPO3Rector\TYPO312\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->ruleWithConfiguration(IgnorePageTypeRestrictionRector::class, []);
    $rectorConfig->rule(MoveAllowTableOnStandardPagesToTCAConfigurationRector::class);
};
