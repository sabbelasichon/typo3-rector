<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\TYPO314\v0\UsageOfValidationAttributesAtMethodLevelRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->rule(UsageOfValidationAttributesAtMethodLevelRector::class);
};
