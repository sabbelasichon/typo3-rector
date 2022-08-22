<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v12\v0\RemoveDisableCharsetHeaderConfigTypoScriptRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveDisablePageExternalUrlOptionRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveMetaCharSetRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSendCacheHeadersConfigOptionRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSpamProtectEmailAddressesAsciiOptionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemoveDisableCharsetHeaderConfigTypoScriptRector::class);
    $rectorConfig->rule(RemoveSpamProtectEmailAddressesAsciiOptionRector::class);
    $rectorConfig->rule(RemoveSendCacheHeadersConfigOptionRector::class);
    $rectorConfig->rule(RemoveDisablePageExternalUrlOptionRector::class);
    $rectorConfig->rule(RemoveMetaCharSetRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveConfigDoctypeSwitchRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typoscript\UseConfigArrayForTSFEPropertiesRector::class);
};
