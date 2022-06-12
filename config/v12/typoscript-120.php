<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveDisablePageExternalUrlOptionRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSpamProtectEmailAddressesAsciiOptionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemoveSpamProtectEmailAddressesAsciiOptionRector::class);
    $rectorConfig->rule(RemoveDisablePageExternalUrlOptionRector::class);
};
