<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateColsToSizeForTcaTypeNoneRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateEvalIntAndDouble2ToTypeNumberRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateInputDateTimeRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateInternalTypeFolderToTypeFolderRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateNullFlagRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigratePasswordAndSaltedPasswordToPasswordTypeRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateRenderTypeColorpickerToTypeColorRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateRequiredFlagRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateToEmailTypeRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveCruserIdRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveTableLocalPropertyRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveTCAInterfaceAlwaysDescriptionRector;

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
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO312\v0\MigrateFileFieldTCAConfigToTCATypeFileRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO312\v0\MigrateRenderTypeInputLinkToTypeLinkRector::class);
};
