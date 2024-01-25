<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v12\v0\RemoveDisableCharsetHeaderConfigTypoScriptRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveConfigDoctypeSwitchRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveDisablePageExternalUrlOptionRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveMetaCharSetRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveNewContentElementWizardOptionsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSendCacheHeadersConfigOptionRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSpamProtectEmailAddressesAsciiOptionRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveTSConfigModesRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RenameMailLinkHandlerKeyRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\UseConfigArrayForTSFEPropertiesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(RemoveDisableCharsetHeaderConfigTypoScriptRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveSpamProtectEmailAddressesAsciiOptionRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveSendCacheHeadersConfigOptionRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveDisablePageExternalUrlOptionRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveMetaCharSetRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveConfigDoctypeSwitchRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(UseConfigArrayForTSFEPropertiesRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveTSConfigModesRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RemoveNewContentElementWizardOptionsRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(RenameMailLinkHandlerKeyRector::class)->tag('typo3_rector.typoscript_rectors');
};
