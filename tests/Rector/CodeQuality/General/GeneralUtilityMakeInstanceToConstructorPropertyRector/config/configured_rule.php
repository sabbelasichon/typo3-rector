<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\AnotherService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    if (\PHP_VERSION_ID >= PhpVersion::PHP_81) {
        $rectorConfig->phpVersion(PhpVersion::PHP_81);
    } elseif (\PHP_VERSION_ID >= PhpVersion::PHP_80) {
        $rectorConfig->phpVersion(PhpVersion::PHP_80);
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
