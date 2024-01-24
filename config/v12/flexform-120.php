<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateEmailFlagToEmailTypeFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateInternalTypeFolderToTypeFolderFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateNullFlagFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateRenderTypeColorpickerToTypeColorFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateRequiredFlagFlexFormRector;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\RemoveElementTceFormsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $services = $rectorConfig->services();
    $services->set(MigrateEmailFlagToEmailTypeFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
    $services->set(MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
    $services->set(MigrateInternalTypeFolderToTypeFolderFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
    $services->set(MigrateNullFlagFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
    $services->set(MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector::class)->tag(
        'typo3_rector.flexform_rectors'
    );
    $services->set(MigrateRenderTypeColorpickerToTypeColorFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
    $services->set(MigrateRequiredFlagFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
    $services->set(RemoveElementTceFormsRector::class)->tag('typo3_rector.flexform_rectors');
};
