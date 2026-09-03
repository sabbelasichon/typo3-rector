<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v0\AddAsNonSchedulableCommandAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->symfonyContainerXml(__DIR__ . '/../xml/services.xml');
    $rectorConfig->rule(AddAsNonSchedulableCommandAttributeRector::class);
};
