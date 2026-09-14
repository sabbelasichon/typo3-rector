<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\TYPO312\v3\UseSelectItemInsteadOfArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    if (PHP_VERSION_ID >= PhpVersionFeature::NAMED_ARGUMENTS) {
        $rectorConfig->phpVersion(PhpVersion::PHP_80);
    } else {
        $rectorConfig->phpVersion(PhpVersion::PHP_74);
    }

    $rectorConfig->rule(UseSelectItemInsteadOfArrayRector::class);
};
