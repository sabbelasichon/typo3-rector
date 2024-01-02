<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateColsToSizeForTcaTypeNoneRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateEvalIntAndDouble2ToTypeNumberRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateInputDateTimeRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateInternalTypeFolderToTypeFolderRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateNullFlagRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigratePasswordAndSaltedPasswordToPasswordTypeRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateRenderTypeColorpickerToTypeColorRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateRequiredFlagRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateToEmailTypeRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveCruserIdRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveTableLocalPropertyRector;
use Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveTCAInterfaceAlwaysDescriptionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateColsToSizeForTcaTypeNoneRector::class);
    $rectorConfig->rule(MigrateEvalIntAndDouble2ToTypeNumberRector::class);
    $rectorConfig->rule(MigrateInputDateTimeRector::class);
    $rectorConfig->rule(MigrateInternalTypeFolderToTypeFolderRector::class);
    $rectorConfig->rule(MigrateNullFlagRector::class);
    $rectorConfig->rule(MigratePasswordAndSaltedPasswordToPasswordTypeRector::class);
    $rectorConfig->rule(MigrateRenderTypeColorpickerToTypeColorRector::class);
    $rectorConfig->rule(MigrateRequiredFlagRector::class);
    $rectorConfig->rule(MigrateToEmailTypeRector::class);
    $rectorConfig->rule(RemoveCruserIdRector::class);
    $rectorConfig->rule(RemoveTableLocalPropertyRector::class);
    $rectorConfig->rule(RemoveTCAInterfaceAlwaysDescriptionRector::class);
};
