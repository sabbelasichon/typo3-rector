<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateFileTypeConstantsToFileTypeEnumRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->rule(MigrateFileTypeConstantsToFileTypeEnumRector::class);
};
