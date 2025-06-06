<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\TYPO313\v4\MigrateDataProviderContextGettersAndSettersRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->rule(MigrateDataProviderContextGettersAndSettersRector::class);
};
