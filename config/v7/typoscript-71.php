<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v7\v1\AdditionalHeadersToArrayTypoScriptRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(AdditionalHeadersToArrayTypoScriptRector::class)->tag('typo3_rector.typoscript_rectors');
};
