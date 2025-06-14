<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\AnotherService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
        $rectorConfig->phpVersion(PhpVersionFeature::READONLY_PROPERTY);
    } elseif (\PHP_VERSION_ID >= PhpVersionFeature::PROPERTY_PROMOTION) {
        $rectorConfig->phpVersion(PhpVersionFeature::PROPERTY_PROMOTION);
    } else {
        $rectorConfig->phpVersion(PhpVersion::PHP_74);
    }
    $rectorConfig->ruleWithConfiguration(
        GeneralUtilityMakeInstanceToConstructorPropertyRector::class,
        [
            GeneralUtilityMakeInstanceToConstructorPropertyRector::ALLOWED_CLASSES => [
                MyService::class,
                AnotherService::class,
            ],
        ]
    );
};
