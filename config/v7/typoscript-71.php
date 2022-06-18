<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v7\v1\AdditionalHeadersToArrayTypoScriptRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(AdditionalHeadersToArrayTypoScriptRector::class);
};
