<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(TemplateServiceToServerRequestFrontendTypoScriptAttributeRector::class);
};
