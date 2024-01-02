<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateInternalTypeFolderToTypeFolderFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateNullFlagFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateRenderTypeColorpickerToTypeColorFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\RemoveElementTceFormsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector::class);
    $rectorConfig->rule(MigrateInternalTypeFolderToTypeFolderFlexFormRector::class);
    $rectorConfig->rule(MigrateNullFlagFlexFormRector::class);
    $rectorConfig->rule(MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector::class);
    $rectorConfig->rule(MigrateRenderTypeColorpickerToTypeColorFlexFormRector::class);
    $rectorConfig->rule(RemoveElementTceFormsRector::class);
};
